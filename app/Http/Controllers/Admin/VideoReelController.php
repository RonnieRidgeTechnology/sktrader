<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VideoReel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class VideoReelController extends Controller
{
    public function index()
    {
        $reels = VideoReel::orderBy('sort_order')->orderBy('id')->paginate(20);
        return Inertia::render('Admin/VideoReels/Index', ['reels' => $reels]);
    }

    public function create()
    {
        return Inertia::render('Admin/VideoReels/Form', ['reel' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'file_path'  => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status'     => 'boolean',
            'video'      => 'nullable|file|mimes:mp4,mov,webm|max:102400',
        ]);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['status'] = $request->boolean('status', true);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = $file->getClientOriginalName() ?: 'reel-' . time() . '.' . $file->getClientOriginalExtension();
            $dir = public_path('media/video');
            File::ensureDirectoryExists($dir);
            $file->move($dir, $name);
            $validated['file_path'] = $name;
        }

        VideoReel::create($validated);
        return redirect()->route('admin.video-reels.index')->with('success', 'Video reel created.');
    }

    public function edit(VideoReel $videoReel)
    {
        return Inertia::render('Admin/VideoReels/Form', ['reel' => $videoReel]);
    }

    public function update(Request $request, VideoReel $videoReel)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'file_path'  => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'status'     => 'boolean',
            'video'      => 'nullable|file|mimes:mp4,mov,webm|max:102400',
        ]);
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['status'] = $request->boolean('status', true);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $name = $file->getClientOriginalName() ?: 'reel-' . time() . '.' . $file->getClientOriginalExtension();
            $dir = public_path('media/video');
            File::ensureDirectoryExists($dir);
            $file->move($dir, $name);
            $validated['file_path'] = $name;
        }

        $videoReel->update($validated);
        return redirect()->route('admin.video-reels.index')->with('success', 'Video reel updated.');
    }

    public function destroy(VideoReel $videoReel)
    {
        $videoReel->delete();
        return redirect()->route('admin.video-reels.index')->with('success', 'Video reel deleted.');
    }
}
