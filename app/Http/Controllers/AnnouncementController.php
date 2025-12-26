<?php

namespace App\Http\Controllers;

use App\Jobs\SendAnnouncementToClients;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Restrict Client role from accessing this page
        if (auth()->user()->hasRole('Client')) {
            abort(403, 'Unauthorized access.');
        }

        $announcements = Announcement::latest()->get();
        return view('pages.announcement.index', compact('announcements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        // Dispatch job to send emails to all clients
        SendAnnouncementToClients::dispatch($announcement);

        return response()->json([
            'success' => true,
            'message' => 'Announcement created successfully! Emails are being sent to all clients.',
            'data' => $announcement
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $announcement
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $announcement->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Announcement updated successfully!',
            'data' => $announcement
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement): JsonResponse
    {
        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully!'
        ]);
    }
}
