<?php

namespace App\Http\Controllers;

use App\Models\AlbumPhoto;
use App\Models\WeddingAlbum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $query = WeddingAlbum::where('is_public', true)->withCount('photos');

        if ($request->has('q') && !empty($request->q)) {
            $search = '%' . $request->q . '%';
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', $search)
                  ->orWhere('couple_names', 'like', $search)
                  ->orWhere('location', 'like', $search);
            });
        }

        $albums = $query->orderBy('event_date', 'desc')->latest()->get();

        return view('galleries.index', [
            'albums' => $albums,
            'search' => $request->q,
        ]);
    }

    public function clientPortalGate(): View
    {
        return view('galleries.client-portal-gate');
    }

    public function unlockClientPortal(Request $request): JsonResponse
    {
        $pin = trim($request->input('pin', ''));

        if (empty($pin)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter your 4-digit passcode.',
            ], 422);
        }

        $album = WeddingAlbum::where('pin_code', $pin)->first();

        if (!$album) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid passcode. Please check the PIN provided by Paneventz.',
            ], 422);
        }

        // Authorize this album in the client's session
        session()->put("gallery_auth_{$album->id}", true);

        return response()->json([
            'success'      => true,
            'redirect_url' => route('gallery.show', $album->slug),
            'couple'       => $album->couple_names ?: $album->title,
        ]);
    }

    public function show(string $slug): View
    {
        $album = WeddingAlbum::where('slug', $slug)->firstOrFail();

        $needsPin = !empty($album->pin_code) && !session()->get("gallery_auth_{$album->id}");

        $photos = $album->photos()->latest()->get();

        return view('galleries.guest-portal', [
            'album'    => $album,
            'photos'   => $photos,
            'needsPin' => $needsPin,
        ]);
    }

    public function verifyPin(Request $request, string $slug): JsonResponse
    {
        $album = WeddingAlbum::where('slug', $slug)->firstOrFail();

        $pin = trim($request->input('pin', ''));

        if ($pin === (string) $album->pin_code) {
            session()->put("gallery_auth_{$album->id}", true);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid PIN code. Please check with the couple.'], 422);
    }

    public function getPhotosData(string $slug): JsonResponse
    {
        $album = WeddingAlbum::where('slug', $slug)->firstOrFail();

        if (!empty($album->pin_code) && !session()->get("gallery_auth_{$album->id}")) {
            return response()->json(['error' => 'PIN required'], 403);
        }

        $photos = $album->photos()
            ->select('id', 'photo_url', 'thumbnail_url', 'file_name', 'is_video', 'file_size', 'face_descriptors', 'faces_count')
            ->get()
            ->map(fn ($p) => [
                'id'               => $p->id,
                'url'              => $p->full_url,
                'download_url'     => route('gallery.download', $p->id),
                'thumbnail_url'    => $p->thumbnail_url ? (str_starts_with($p->thumbnail_url, 'http') ? $p->thumbnail_url : asset('storage/' . $p->thumbnail_url)) : $p->full_url,
                'file_name'        => $p->file_name,
                'is_video'         => (bool) $p->is_video,
                'file_size'        => $p->file_size,
                'face_descriptors' => $p->face_descriptors ?? [],
                'faces_count'      => $p->faces_count,
            ]);

        return response()->json([
            'album'  => [
                'id'           => $album->id,
                'title'        => $album->title,
                'couple_names' => $album->couple_names,
            ],
            'photos' => $photos,
        ]);
    }

    public function downloadFile(int $photoId)
    {
        $photo = AlbumPhoto::with('album')->findOrFail($photoId);
        $album = $photo->album;

        // Verify PIN access if album requires PIN
        if (!empty($album->pin_code) && !session()->get("gallery_auth_{$album->id}")) {
            abort(403, 'Unauthorized. Please unlock the gallery with the PIN code first.');
        }

        // Determine filename
        $ext = $photo->is_video ? 'mp4' : 'jpg';
        if (!empty($photo->file_name)) {
            $fileName = $photo->file_name;
            if (!str_contains($fileName, '.')) {
                $fileName .= '.' . $ext;
            }
        } else {
            $fileName = 'Paneventz-' . ($album->slug ?: 'wedding') . '-' . $photo->id . '.' . $ext;
        }

        // 1. Local storage file
        if (!str_starts_with($photo->photo_url, 'http://') && !str_starts_with($photo->photo_url, 'https://')) {
            $path = storage_path('app/public/' . $photo->photo_url);
            if (file_exists($path)) {
                return response()->download($path, $fileName);
            }
        }

        // 2. Google Drive / Remote CDN file: Stream download without exposing Google Drive URL
        $remoteUrl = $photo->photo_url;
        if (preg_match('#[?&]id=([a-zA-Z0-9_-]+)#', $remoteUrl, $m) || preg_match('#/d/([a-zA-Z0-9_-]+)#', $remoteUrl, $m)) {
            $driveId = $m[1];
            $remoteUrl = "https://drive.google.com/uc?export=download&id={$driveId}";
        }

        try {
            $ctx = stream_context_create([
                'http' => [
                    'follow_location' => 1,
                    'max_redirects' => 5,
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36',
                ],
            ]);
            $stream = @fopen($remoteUrl, 'r', false, $ctx);
            if ($stream) {
                $contentType = $photo->is_video ? 'video/mp4' : 'image/jpeg';
                return response()->stream(function () use ($stream) {
                    fpassthru($stream);
                    fclose($stream);
                }, 200, [
                    'Content-Type'        => $contentType,
                    'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    'Cache-Control'       => 'private, no-transform, no-store, must-revalidate',
                ]);
            }
        } catch (\Throwable $e) {
            return redirect()->away($remoteUrl);
        }

        return redirect()->away($remoteUrl);
    }

    public function saveDescriptors(Request $request, int $photoId): JsonResponse
    {
        $validated = $request->validate([
            'descriptors' => 'required|array',
        ]);

        $photo = AlbumPhoto::findOrFail($photoId);
        $photo->update([
            'face_descriptors' => $validated['descriptors'],
            'faces_count'      => count($validated['descriptors']),
        ]);

        return response()->json(['success' => true, 'faces_count' => $photo->faces_count]);
    }
}