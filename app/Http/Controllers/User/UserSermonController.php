<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Sermon;
use Illuminate\Http\Request;

class UserSermonController extends Controller
{
    /**
     * Display a listing of sermons for users
     */
    public function index(Request $request)
    {
        $query = Sermon::orderBy('sermon_date', 'desc');
        
        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('sermon_theme', 'LIKE', "%{$search}%")
                  ->orWhere('reverend_name', 'LIKE', "%{$search}%")
                  ->orWhere('scripture_references', 'LIKE', "%{$search}%")
                  ->orWhere('sermon_description', 'LIKE', "%{$search}%");
            });
        }
        
        // Category/filter
        if ($request->has('category') && $request->category && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Filter by date
        if ($request->has('date_filter')) {
            $dateFilter = $request->date_filter;
            $today = now()->startOfDay();
            
            switch($dateFilter) {
                case 'today':
                    $query->whereDate('sermon_date', $today);
                    break;
                case 'week':
                    $query->where('sermon_date', '>=', $today->copy()->subDays(7));
                    break;
                case 'month':
                    $query->where('sermon_date', '>=', $today->copy()->subMonth());
                    break;
            }
        }

        // Filter by video availability
        if ($request->has('has_video') && $request->has_video == '1') {
            $query->whereNotNull('video_path');
        }

        // Filter by AI processed
        if ($request->has('ai_processed') && $request->ai_processed == '1') {
            $query->where('processing_status', 'completed');
        }
        
        $sermons = $query->paginate(12);
        
        // Categories for filter
        $categories = ['Faith', 'Grace', 'Life', 'Love', 'Hope', 'Peace', 'Prayer', 'Worship'];
        
        // Stats for dashboard
        $stats = [
            'total' => Sermon::count(),
            'with_video' => Sermon::whereNotNull('video_path')->count(),
            'ai_processed' => Sermon::where('processing_status', 'completed')->count(),
            'this_month' => Sermon::whereMonth('sermon_date', now()->month)->count(),
        ];
        
        return view('user.sermon.index', compact('sermons', 'categories', 'stats'));
    }

    /**
     * Show a single sermon
     */
    public function show($id)
    {
        $sermon = Sermon::findOrFail($id);
        
        // Get related sermons by same reverend or similar scripture
        $relatedSermons = Sermon::where('id', '!=', $id)
            ->where(function($query) use ($sermon) {
                $query->where('reverend_name', $sermon->reverend_name)
                      ->orWhere('scripture_references', 'LIKE', '%' . explode(',', $sermon->scripture_references ?? '')[0] . '%');
            })
            ->orderBy('sermon_date', 'desc')
            ->limit(4)
            ->get();
        
        // Get recent sermons if no related found
        if ($relatedSermons->count() < 3) {
            $additionalSermons = Sermon::where('id', '!=', $id)
                ->whereNotIn('id', $relatedSermons->pluck('id'))
                ->orderBy('sermon_date', 'desc')
                ->limit(4 - $relatedSermons->count())
                ->get();
            
            $relatedSermons = $relatedSermons->merge($additionalSermons);
        }
        
        return view('user.sermon.show', compact('sermon', 'relatedSermons'));
    }

    /**
     * Get processing status for a sermon (for AJAX polling)
     */
    public function processingStatus($id)
    {
        $sermon = Sermon::findOrFail($id);
        
        return response()->json([
            'status' => $sermon->processing_status,
            'has_transcript' => !empty($sermon->ai_transcript),
            'has_summary' => !empty($sermon->ai_summary),
            'has_key_points' => !empty($sermon->ai_key_points),
            'has_translations' => !empty($sermon->ai_translations),
            'error' => $sermon->processing_error,
        ]);
    }
}