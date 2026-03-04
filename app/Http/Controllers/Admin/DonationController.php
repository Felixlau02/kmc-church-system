<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DonationController extends Controller
{
    // Show donations list & summary (EXCLUDE QR CODES)
    public function index()
    {
        // Get only REAL donations (use a dedicated flag or check for donor_name)
        $donations = Donation::whereNotNull('donor_name')
                            ->whereNotNull('amount')
                            ->where('amount', '>', 0)
                            ->whereNull('qr_code_label')
                            ->latest()
                            ->get();
        
        $totalAmount = Donation::whereNotNull('donor_name')
                              ->whereNotNull('amount')
                              ->where('amount', '>', 0)
                              ->whereNull('qr_code_label')
                              ->sum('amount');
        
        $totalDonors = Donation::whereNotNull('donor_name')
                              ->whereNotNull('amount')
                              ->where('amount', '>', 0)
                              ->whereNull('qr_code_label')
                              ->distinct('donor_name')
                              ->count('donor_name');
        
        // Get QR codes SEPARATELY (only ONE)
        $qrCodes = Donation::whereNotNull('qr_code_path')
                          ->whereNotNull('qr_code_label')
                          ->where('qr_code_path', '!=', '')
                          ->latest()
                          ->limit(1)
                          ->get();

        return view('admin.donation.index', compact('donations', 'totalAmount', 'totalDonors', 'qrCodes'));
    }

    // Show QR Code management page
    public function manageQR()
    {
        // Get only ONE QR code (the latest)
        $qrCode = Donation::whereNotNull('qr_code_path')
                          ->whereNotNull('qr_code_label')
                          ->where('qr_code_path', '!=', '')
                          ->latest()
                          ->first();
        
        return view('admin.donation.manage-qr', compact('qrCode'));
    }

    // Store/Upload QR Code
    public function storeQR(Request $request)
    {
        // Check if QR code already exists
        $existingQR = Donation::whereNotNull('qr_code_path')
                             ->whereNotNull('qr_code_label')
                             ->where('qr_code_path', '!=', '')
                             ->first();
        
        if ($existingQR) {
            return back()->with('error', 'Only one QR code is allowed. Please delete the existing QR code first.');
        }

        $request->validate([
            'qr_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_label' => 'required|string|max:255',
        ]);

        if ($request->hasFile('qr_image')) {
            // Store the file with a unique name
            $file = $request->file('qr_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('qr-codes', $filename, 'public');

            // Create NEW donation record for QR storage
            Donation::create([
                'qr_code_path' => $path,
                'qr_code_label' => $request->qr_label,
                'donor_name' => null,
                'email' => null,
                'amount' => null,
                'payment_method' => null,
                'note' => 'QR Code: ' . $request->qr_label,
                'evidence_path' => null,
            ]);

            return redirect()->route('admin.donation.manage-qr')
                           ->with('success', 'QR Code uploaded successfully.');
        }

        return back()->with('error', 'Failed to upload QR Code.');
    }

    // Delete QR Code
    public function deleteQR($id)
    {
        $donation = Donation::find($id);

        if ($donation && $donation->qr_code_path) {
            // Delete from storage
            Storage::disk('public')->delete($donation->qr_code_path);
            
            // Delete the database record
            $donation->delete();

            return back()->with('success', 'QR Code deleted successfully.');
        }

        return back()->with('error', 'QR Code not found.');
    }

    // Store a REAL donation (only for user/admin entry)
    public function store(Request $request)
    {
        $request->validate([
            'donor_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
            'evidence' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120', // ✅ Fixed: Changed from 'image' to 'file'
        ], [
            'evidence.required' => 'Please upload a receipt or screenshot as evidence.',
            'evidence.file' => 'Evidence must be a file.',
            'evidence.mimes' => 'Evidence must be a JPEG, PNG, JPG, GIF, or PDF file.',
            'evidence.max' => 'Evidence file size must not exceed 5MB.',
        ]);

        // Store evidence file
        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $evidencePath = $file->storeAs('donation-evidence', $filename, 'public');
        }

        // Create a real donation
        Donation::create([
            'donor_name' => $request->donor_name,
            'email' => $request->email,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
            'qr_code_path' => null,
            'qr_code_label' => null,
            'evidence_path' => $evidencePath,
        ]);

        return redirect()->route('admin.donation.index')
                       ->with('success', 'Donation recorded successfully.');
    }

    // Delete a donation
    public function destroy($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return back()->with('error', 'Donation not found.');
        }

        // Make sure it's a real donation, not a QR code
        if (!$donation->isDonation()) {
            return back()->with('error', 'Cannot delete this record.');
        }

        // Delete evidence file if exists
        if ($donation->evidence_path) {
            Storage::disk('public')->delete($donation->evidence_path);
        }

        // Delete the donation record
        $donation->delete();

        return back()->with('success', 'Donation deleted successfully.');
    }

    // View evidence
    public function viewEvidence($id)
    {
        $donation = Donation::findOrFail($id);
        
        if (!$donation->evidence_path) {
            abort(404, 'Evidence not found.');
        }

        $path = storage_path('app/public/' . $donation->evidence_path);
        
        if (!file_exists($path)) {
            abort(404, 'Evidence file not found.');
        }

        return response()->file($path);
    }

    // Generate monthly report
    public function generateReport(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        // Get donations for the specified month
        $donations = Donation::whereNotNull('donor_name')
                            ->whereNotNull('amount')
                            ->where('amount', '>', 0)
                            ->whereNull('qr_code_label')
                            ->whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->orderBy('created_at', 'desc')
                            ->get();

        $totalAmount = $donations->sum('amount');
        $totalDonations = $donations->count();
        $uniqueDonors = $donations->unique('donor_name')->count();
        
        // Group by payment method
        $byPaymentMethod = $donations->groupBy('payment_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount')
            ];
        });

        // Get available months and years for dropdown
        $availableMonths = Donation::whereNotNull('donor_name')
                                  ->whereNotNull('amount')
                                  ->where('amount', '>', 0)
                                  ->whereNull('qr_code_label')
                                  ->selectRaw('DISTINCT MONTH(created_at) as month, YEAR(created_at) as year')
                                  ->orderBy('year', 'desc')
                                  ->orderBy('month', 'desc')
                                  ->get();

        return view('admin.donation.report', compact(
            'donations', 
            'totalAmount', 
            'totalDonations', 
            'uniqueDonors',
            'byPaymentMethod',
            'month', 
            'year',
            'availableMonths'
        ));
    }
}