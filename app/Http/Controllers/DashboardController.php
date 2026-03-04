<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect admin users to admin dashboard
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.home');
        }
        
        // Regular users redirect to user dashboard
        return redirect()->route('user.dashboard');
    }
}