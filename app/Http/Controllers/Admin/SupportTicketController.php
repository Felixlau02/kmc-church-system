<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTicket;

class SupportTicketController extends Controller
{
    // View all support tickets from all users
    public function index(Request $request)
    {
        $query = SupportTicket::with('user');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by subject or message
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Get statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::where('status', 'Open')->count(),
            'pending' => SupportTicket::where('status', 'Pending')->count(),
            'closed' => SupportTicket::where('status', 'Closed')->count(),
        ];

        return view('admin.support.index', compact('tickets', 'stats'));
    }

    // View single ticket details
    public function show($id)
    {
        $ticket = SupportTicket::with('user')->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    // Update ticket status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Open,Pending,Closed',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['status' => $validated['status']]);

        return redirect()->back()
            ->with('success', 'Ticket status updated successfully!');
    }

    // Add response to ticket
    public function addResponse(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        
        $ticket->update([
            'admin_response' => $validated['admin_response'],
            'status' => 'Pending', // Automatically mark as pending when response is added
        ]);
        
        return redirect()->back()
            ->with('success', 'Response added successfully and ticket marked as Pending!');
    }

    // Update existing response
    public function updateResponse(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['admin_response' => $validated['admin_response']]);
        
        return redirect()->back()
            ->with('success', 'Response updated successfully!');
    }

    // Delete response
    public function deleteResponse($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->update(['admin_response' => null]);
        
        return redirect()->back()
            ->with('success', 'Response deleted successfully!');
    }

    // Delete ticket
    public function destroy($id)
    {
        try {
            $ticket = SupportTicket::findOrFail($id);
            $ticket->delete();
            
            return redirect()->route('admin.support.index')
                ->with('success', 'Support ticket deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.support.index')
                ->with('error', 'Failed to delete support ticket: ' . $e->getMessage());
        }
    }

    // Bulk update tickets
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ticket_ids' => 'required|array',
            'action' => 'required|in:close,delete,pending',
        ]);

        try {
            if ($validated['action'] === 'delete') {
                SupportTicket::whereIn('id', $validated['ticket_ids'])->delete();
                $message = 'Selected tickets deleted successfully!';
            } elseif ($validated['action'] === 'close') {
                SupportTicket::whereIn('id', $validated['ticket_ids'])
                    ->update(['status' => 'Closed']);
                $message = 'Selected tickets closed successfully!';
            } elseif ($validated['action'] === 'pending') {
                SupportTicket::whereIn('id', $validated['ticket_ids'])
                    ->update(['status' => 'Pending']);
                $message = 'Selected tickets marked as pending!';
            }

            return redirect()->route('admin.support.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            return redirect()->route('admin.support.index')
                ->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}