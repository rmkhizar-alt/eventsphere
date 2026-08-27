<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MediaGallery;
use App\Models\Bookmark;
use App\Models\SavedMedia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MediaGalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaGallery::query();

        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->has('file_type')) {
            $query->where('file_type', $request->file_type);
        }

        if ($request->has('approved')) {
            $query->where('is_approved', $request->approved === 'true');
        }

        if ($request->has('uploader_id')) {
            $query->where('user_id', $request->uploader_id);
        }

        $media = $query->with(['event', 'uploader'])
            ->latest()
            ->paginate(20);

        return response()->json(['data' => $media]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'file_type' => 'required|string|max:50',
            'file_path' => 'required|string',
            'title' => 'nullable|string|max:255',
            'is_approved' => 'sometimes|boolean',
        ]);

        $media = MediaGallery::create([
            'event_id' => $request->event_id,
            'user_id' => $request->user()->id,
            'file_type' => $request->file_type,
            'file_path' => $request->file_path,
            'title' => $request->title,
            'is_approved' => $request->filled('is_approved') ? $request->is_approved : false,
        ]);

        return response()->json(['data' => $media, 'message' => 'Media uploaded']);
    }

    public function show(MediaGallery $media)
    {
        return response()->json(['data' => $media->load(['event', 'uploader'])]);
    }

    public function update(Request $request, MediaGallery $media)
    {
        $this->authorize('update', $media);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'is_approved' => 'sometimes|boolean',
        ]);

        $media->update($request->only(['title', 'is_approved']));

        return response()->json(['data' => $media, 'message' => 'Media updated']);
    }

    public function destroy(MediaGallery $media)
    {
        $this->authorize('delete', $media);
        $media->delete();

        return response()->json(['message' => 'Media deleted']);
    }

    public function bookmark(Request $request, MediaGallery $media)
    {
        $existing = Bookmark::where('event_id', $media->event_id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['message' => 'Removed from bookmarks']);
        }

        Bookmark::create([
            'event_id' => $media->event_id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Bookmarked']);
    }

    public function save(Request $request, MediaGallery $media)
    {
        $existing = SavedMedia::where('media_id', $media->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['message' => 'Removed from saved']);
        }

        SavedMedia::create([
            'media_id' => $media->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Saved to media library']);
    }

    public function approve(Request $request, MediaGallery $media)
    {
        $this->authorize('approve', $media);

        $media->update(['is_approved' => true]);

        return response()->json(['data' => $media, 'message' => 'Media approved']);
    }

    public function byEvent(Event $event)
    {
        $media = MediaGallery::where('event_id', $event->id)
            ->with(['uploader'])
            ->latest()
            ->paginate(20);

        return response()->json(['data' => $media]);
    }
}