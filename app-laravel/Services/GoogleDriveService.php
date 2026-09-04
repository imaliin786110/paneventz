<?php

namespace App\Services;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    /**
     * Extracts folder ID from full URL or returns raw ID.
     */
    public static function extractFolderId(string $input): ?string
    {
        $input = trim($input);

        // Pattern for drive.google.com/drive/folders/ID or folders/ID
        if (preg_match('#folders/([a-zA-Z0-9_-]+)#', $input, $matches)) {
            return $matches[1];
        }

        // Pattern for ?id=ID
        if (preg_match('#[?&]id=([a-zA-Z0-9_-]+)#', $input, $matches)) {
            return $matches[1];
        }

        // If it looks like a direct ID (alphanumeric with dashes/underscores, min 15 chars)
        if (preg_match('#^[a-zA-Z0-9_-]{15,}$#', $input)) {
            return $input;
        }

        return null;
    }

    /**
     * Fetches photo files from a Google Drive folder.
     */
    public static function fetchPhotosFromFolder(string $folderInput, ?string $apiKey = null): array
    {
        $folderId = self::extractFolderId($folderInput);
        if (!$folderId) {
            throw new \Exception('Invalid Google Drive folder link or folder ID.');
        }

        if (empty($apiKey)) {
            $setting = WebsiteSetting::first();
            $apiKey = $setting?->google_drive_api_key;
        }

        // 1. Try Google Drive API v3 if API key is provided
        if (!empty($apiKey)) {
            $apiUrl = "https://www.googleapis.com/drive/v3/files";
            $response = Http::get($apiUrl, [
                'q'        => "'{$folderId}' in parents and trashed = false and (mimeType contains 'image/' or mimeType contains 'video/')",
                'key'      => $apiKey,
                'fields'   => 'files(id,name,mimeType,size)',
                'pageSize' => 1000,
            ]);

            if ($response->successful()) {
                $files = $response->json('files', []);
                $results = [];
                foreach ($files as $file) {
                    $id = $file['id'];
                    $name = $file['name'] ?? 'file';
                    $mime = $file['mimeType'] ?? '';
                    $isVideo = str_starts_with($mime, 'video/') || (bool) preg_match('/\.(mp4|mov|webm|avi|mkv|m4v)$/i', $name);

                    $results[] = [
                        'file_id'       => $id,
                        'name'          => $name,
                        'is_video'      => $isVideo,
                        'file_size'     => isset($file['size']) ? self::formatBytes((int)$file['size']) : null,
                        'photo_url'     => $isVideo ? "https://drive.google.com/uc?export=download&id={$id}" : "https://lh3.googleusercontent.com/d/{$id}=w1600",
                        'thumbnail_url' => "https://lh3.googleusercontent.com/d/{$id}=w400",
                        'download_url'  => "https://drive.google.com/uc?export=download&id={$id}",
                    ];
                }
                return $results;
            } else {
                Log::warning('Google Drive API query failed: ' . $response->body());
            }
        }

        // 2. Fallback: Parse public Google Drive folder web page
        try {
            $folderUrl = "https://drive.google.com/drive/folders/{$folderId}";
            $res = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ])->get($folderUrl);

            if ($res->successful()) {
                $html = $res->body();
                // Match file IDs pattern inside drive folder page
                preg_match_all('#\["([a-zA-Z0-9_-]{25,})",\["([^\"]+)"#', $html, $matches, PREG_SET_ORDER);
                
                $results = [];
                $seen = [];
                foreach ($matches as $m) {
                    $id = $m[1];
                    $name = $m[2];
                    if (!isset($seen[$id]) && !empty($id) && strlen($id) >= 25 && $id !== $folderId) {
                        $seen[$id] = true;
                        $isVideo = (bool) preg_match('/\.(mp4|mov|webm|avi|mkv|m4v)$/i', $name);
                        $results[] = [
                            'file_id'       => $id,
                            'name'          => $name ?: ($isVideo ? 'video.mp4' : 'photo.jpg'),
                            'is_video'      => $isVideo,
                            'file_size'     => null,
                            'photo_url'     => $isVideo ? "https://drive.google.com/uc?export=download&id={$id}" : "https://lh3.googleusercontent.com/d/{$id}=w1600",
                            'thumbnail_url' => "https://lh3.googleusercontent.com/d/{$id}=w400",
                            'download_url'  => "https://drive.google.com/uc?export=download&id={$id}",
                        ];
                    }
                }

                if (count($results) > 0) {
                    return $results;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Google Drive public scrape error: ' . $e->getMessage());
        }

        throw new \Exception("Could not fetch photos from this folder. Please ensure the Google Drive folder link sharing is set to 'Anyone with the link can view'. (Or add your free Google Drive API key in Website Settings).");
    }

    public static function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }
}