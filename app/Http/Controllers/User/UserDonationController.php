<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserDonationController extends Controller
{
    // Show donations page with QR codes for users
    public function index()
    {
        // Get all QR codes for display
        $qrCodes = Donation::whereNotNull('qr_code_path')->get();
        
        // Get user's donation history
        $userDonations = Donation::where('email', auth()->user()->email)
                                 ->orWhere('donor_name', auth()->user()->name)
                                 ->where('amount', '>', 0)
                                 ->latest()
                                 ->paginate(10);
        
        // Calculate user statistics
        $totalUserDonation = Donation::where('email', auth()->user()->email)
                                     ->orWhere('donor_name', auth()->user()->name)
                                     ->where('amount', '>', 0)
                                     ->sum('amount');
        
        $userDonationCount = $userDonations->total();

        return view('user.donation.index', compact('qrCodes', 'userDonations', 'totalUserDonation', 'userDonationCount'));
    }

    // Show donation form for users to record donations
    public function create()
    {
        $qrCodes = Donation::whereNotNull('qr_code_path')->get();
        return view('user.donation.create', compact('qrCodes'));
    }

    // Store donation record from user
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
            'note' => 'nullable|string',
            'evidence' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // ✅ Changed from nullable to required
        ], [
            'evidence.required' => 'Please upload a receipt or screenshot as evidence of your donation.',
            'evidence.mimes' => 'Evidence must be a PDF, JPG, JPEG, or PNG file.',
            'evidence.max' => 'Evidence file size must not exceed 5MB.',
        ]);

        $evidencePath = null;
        
        // Handle evidence file upload
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $fileName = time() . '_' . auth()->user()->id . '_' . $file->getClientOriginalName();
            $evidencePath = $file->storeAs('donation_evidence', $fileName, 'public');
        }

        Donation::create([
            'donor_name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'note' => $request->note,
            'evidence_path' => $evidencePath,
        ]);

        return redirect()->route('user.donation.index')
                       ->with('success', 'Thank you for your donation! Your contribution has been recorded.');
    }
}