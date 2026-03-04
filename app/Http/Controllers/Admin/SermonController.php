<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Sermon;
use App\Models\Notification;
use App\Services\FreeAISermonService;
use App\Services\NotificationService;
use App\Jobs\ProcessSermonVideo;

class SermonController extends Controller
{
    protected $aiService;
    protected $notificationService;

    public function __construct(FreeAISermonService $aiService, NotificationService $notificationService)
    {
        $this->aiService = $aiService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $sermons = Sermon::orderBy('sermon_date', 'desc')->paginate(10);
        return view('admin.sermon.index', compact('sermons'));
    }

    public function create()
    {
        return view('admin.sermon.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reverend_name' => 'required|string|max:255',
            'sermon_theme' => 'required|string|max:200',
            'sermon_description' => 'nullable|string|max:1000',
            'sermon_date' => 'required|date',
            'scripture_references' => 'nullable|string|max:500',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sermon = new Sermon();
        $sermon->reverend_name = $request->reverend_name;
        $sermon->sermon_theme = $request->sermon_theme;
        $sermon->sermon_description = $request->sermon_description;
        $sermon->sermon_date = $request->sermon_date;
        $sermon->scripture_references = $request->scripture_references;

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('sermons/videos', 'public');
            $sermon->video_path = $videoPath;
            $sermon->processing_status = 'pending';
        }

        $sermon->save();

        // Send notification to all users
        $this->notificationService->notifyNewSermon($sermon);

        if ($sermon->hasVideo()) {
            ProcessSermonVideo::dispatch($sermon);
            $message = 'Sermon created successfully! Users have been notified. AI is processing your video in the background.';
        } else {
            $message = 'Sermon created successfully! Users have been notified.';
        }

        return redirect()->route('admin.sermon.index')->with('success', $message);
    }

    public function show($id)
    {
        $sermon = Sermon::findOrFail($id);
        return view('admin.sermon.show', compact('sermon'));
    }

    public function edit($id)
    {
        $sermon = Sermon::findOrFail($id);
        return view('admin.sermon.edit', compact('sermon'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reverend_name' => 'required|string|max:255',
            'sermon_theme' => 'required|string|max:200',
            'sermon_description' => 'nullable|string|max:1000',
            'sermon_date' => 'required|date',
            'scripture_references' => 'nullable|string|max:500',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000',
            'ai_transcript' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $sermon = Sermon::findOrFail($id);
        $sermon->reverend_name = $request->reverend_name;
        $sermon->sermon_theme = $request->sermon_theme;
        $sermon->sermon_description = $request->sermon_description;
        $sermon->sermon_date = $request->sermon_date;
        $sermon->scripture_references = $request->scripture_references;

        // Update transcript if provided
        if ($request->has('ai_transcript')) {
            $sermon->ai_transcript = $request->ai_transcript;
        }

        if ($request->hasFile('video')) {
            if ($sermon->video_path) {
                Storage::disk('public')->delete($sermon->video_path);
                Storage::disk('public')->delete($sermon->video_thumbnail);
            }

            $videoPath = $request->file('video')->store('sermons/videos', 'public');
            $sermon->video_path = $videoPath;
            $sermon->processing_status = 'pending';
            $sermon->ai_transcript = null;
            $sermon->ai_summary = null;
            $sermon->ai_key_points = null;
            $sermon->ai_translations = null;
        }

        $sermon->save();

        if ($request->hasFile('video')) {
            ProcessSermonVideo::dispatch($sermon);
            $message = 'Sermon updated successfully! AI is processing your new video.';
        } else {
            $message = 'Sermon updated successfully!';
        }

        return redirect()->route('admin.sermon.index')->with('success', $message);
    }

    public function destroy($id)
    {
        try {
            $sermon = Sermon::findOrFail($id);
            
            // Delete all notifications related to this sermon
            Notification::deleteRelatedNotifications(Sermon::class, $sermon->id);

            // Delete video files if they exist
            if ($sermon->video_path) {
                Storage::disk('public')->delete($sermon->video_path);
            }
            if ($sermon->video_thumbnail) {
                Storage::disk('public')->delete($sermon->video_thumbnail);
            }

            // Delete the sermon
            $sermon->delete();

            session()->flash('notification_deleted', true);

            return redirect()->route('admin.sermon.index')
                ->with('success', 'Sermon and related notifications deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete sermon: ' . $e->getMessage());
        }
    }

    public function processingStatus($id)
    {
        $sermon = Sermon::findOrFail($id);
        
        return response()->json([
            'status' => $sermon->processing_status,
            'has_transcript' => !empty($sermon->ai_transcript),
            'has_summary' => !empty($sermon->ai_summary),
            'has_translations' => !empty($sermon->ai_translations),
            'error' => $sermon->processing_error,
        ]);
    }

    public function reprocess($id)
    {
        $sermon = Sermon::findOrFail($id);
        
        if (!$sermon->hasVideo()) {
            return redirect()->back()->with('error', 'No video to process!');
        }

        $sermon->update(['processing_status' => 'pending']);
        ProcessSermonVideo::dispatch($sermon);

        return redirect()->back()->with('success', 'Reprocessing started!');
    }

    public function downloadTranscript($id)
    {
        $sermon = Sermon::findOrFail($id);
        
        if (!$sermon->ai_transcript) {
            return redirect()->back()->with('error', 'No transcript available!');
        }

        $filename = str_replace(' ', '_', $sermon->sermon_theme) . '_transcript.txt';
        
        return response()->streamDownload(function() use ($sermon) {
            echo $sermon->ai_transcript;
        }, $filename);
    }
}