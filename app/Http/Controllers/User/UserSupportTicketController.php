<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

class UserSupportTicketController extends Controller
{
    /**
     * Display user's support tickets
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.support.index', compact('tickets'));
    }

    /**
     * Show form to create new ticket
     */
    public function create()
    {
        return view('user.support.create');
    }

    /**
     * Store new support ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'Open',
        ]);

        return redirect()->route('user.support.index')
            ->with('success', 'Your support request has been submitted successfully!');
    }

    /**
     * Display specific ticket
     */
    public function show($id)
    {
        $ticket = SupportTicket::where('user_id', auth()->id())
            ->findOrFail($id);

        return view('user.support.show', compact('ticket'));
    }

    /**
     * Delete user's own ticket
     */
    public function destroy($id)
    {
        try {
            $ticket = SupportTicket::where('user_id', auth()->id())
                ->findOrFail($id);
            
            $ticket->delete();
            
            return redirect()->route('user.support.index')
                ->with('success', 'Support ticket deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('user.support.index')
                ->with('error', 'Failed to delete ticket.');
        }
    }
}