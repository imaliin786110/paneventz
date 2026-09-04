<?php

namespace App\Filament\Resources\WeddingAlbums\Pages;

use App\Filament\Resources\WeddingAlbums\WeddingAlbumResource;
use App\Models\AlbumPhoto;
use App\Models\WeddingAlbum;
use App\Services\GoogleDriveService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ManageAlbumPhotos extends Page
{
    use WithFileUploads;

    protected static string $resource = WeddingAlbumResource::class;

    protected string $view = 'filament.resources.wedding-albums.pages.manage-album-photos';

    public WeddingAlbum $record;

    public array $uploadedPhotos = [];

    public ?string $googleDriveInput = null;
    public ?string $guestGoogleDriveInput = null;

    public string $activeTab = 'drive';

    public function mount(WeddingAlbum|int|string $record): void
    {
        if (!($record instanceof WeddingAlbum)) {
            $this->record = WeddingAlbum::findOrFail($record);
        } else {
            $this->record = $record;
        }

        $this->googleDriveInput = $this->record->google_drive_folder_id;
        $this->guestGoogleDriveInput = $this->record->guest_google_drive_folder_id ?: $this->record->google_drive_folder_id;

        $tab = request()->query('tab');
        if (in_array($tab, ['drive', 'ai'])) {
            $this->activeTab = $tab;
        }
    }

    public function setActiveTab(string $tab): void
    {
        if (in_array($tab, ['drive', 'ai'])) {
            $this->activeTab = $tab;
        }
    }

    public function getTitle(): string
    {
        return $this->record->title . ' — Media & Guest AI';
    }

    public function saveUploadedPhotos(): void
    {
        $this->validate([
            'uploadedPhotos.*' => 'image|max:30720', // 30MB per photo
        ]);

        $count = 0;
        foreach ($this->uploadedPhotos as $photo) {
            $path = $photo->store("albums/{$this->record->id}/photos", 'public');

            AlbumPhoto::create([
                'wedding_album_id' => $this->record->id,
                'photo_url'        => $path,
                'file_name'        => $photo->getClientOriginalName(),
                'faces_count'      => 0,
            ]);
            $count++;
        }

        $this->uploadedPhotos = [];

        Notification::make()
            ->title("Uploaded {$count} photos successfully!")
            ->body("Now click 'Scan & Index Faces' to generate AI facial biometric embeddings.")
            ->success()
            ->send();
    }

    public function syncFromGoogleDrive(): void
    {
        $folder = trim($this->googleDriveInput ?? '');
        if (empty($folder)) {
            Notification::make()
                ->title('Please enter a Google Drive folder link or folder ID.')
                ->danger()
                ->send();
            return;
        }

        try {
            $photos = GoogleDriveService::fetchPhotosFromFolder($folder);

            if (empty($photos)) {
                Notification::make()
                    ->title('No images found in this Google Drive folder.')
                    ->warning()
                    ->send();
                return;
            }

            $count = 0;
            foreach ($photos as $p) {
                $photo = AlbumPhoto::firstOrCreate([
                    'wedding_album_id' => $this->record->id,
                    'photo_url'        => $p['photo_url'],
                ], [
                    'thumbnail_url'    => $p['thumbnail_url'],
                    'file_name'        => $p['name'],
                    'is_video'         => $p['is_video'] ?? false,
                    'file_size'        => $p['file_size'] ?? null,
                    'faces_count'      => 0,
                ]);

                if ($photo->wasRecentlyCreated) {
                    $count++;
                }
            }

            $this->record->update(['google_drive_folder_id' => $folder]);

            Notification::make()
                ->title("Connected Google Drive! Synced {$count} media files (photos & videos).")
                ->body("Your clients can now browse and download them directly from your website.")
                ->success()
                ->send();

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Google Drive Sync Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function syncGuestPhotosFromGoogleDrive(): void
    {
        $folder = trim($this->guestGoogleDriveInput ?? '');
        if (empty($folder)) {
            Notification::make()
                ->title('Please enter a Google Drive folder link for Guest Photos.')
                ->danger()
                ->send();
            return;
        }

        try {
            $photos = GoogleDriveService::fetchPhotosFromFolder($folder);

            if (empty($photos)) {
                Notification::make()
                    ->title('No images found in this Google Drive folder.')
                    ->warning()
                    ->send();
                return;
            }

            $count = 0;
            foreach ($photos as $p) {
                $photo = AlbumPhoto::firstOrCreate([
                    'wedding_album_id' => $this->record->id,
                    'photo_url'        => $p['photo_url'],
                ], [
                    'thumbnail_url'    => $p['thumbnail_url'],
                    'file_name'        => $p['name'],
                    'is_video'         => $p['is_video'] ?? false,
                    'file_size'        => $p['file_size'] ?? null,
                    'faces_count'      => 0,
                ]);

                if ($photo->wasRecentlyCreated) {
                    $count++;
                }
            }

            $this->record->update(['guest_google_drive_folder_id' => $folder]);

            Notification::make()
                ->title("Synced {$count} Guest Photos from Google Drive!")
                ->body("Now click 'Step B: Scan & Index Album Faces' below so the AI indexes them for guest selfies.")
                ->success()
                ->send();

        } catch (\Throwable $e) {
            Notification::make()
                ->title('Guest Google Drive Sync Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function deletePhoto(int $photoId): void
    {
        $photo = AlbumPhoto::where('wedding_album_id', $this->record->id)->find($photoId);
        if ($photo) {
            if (!str_starts_with($photo->photo_url, 'http')) {
                Storage::disk('public')->delete($photo->photo_url);
            }
            $photo->delete();

            Notification::make()
                ->title('Photo removed')
                ->success()
                ->send();
        }
    }

    public function savePhotoFaces(int $photoId, array $descriptors): void
    {
        $photo = AlbumPhoto::where('wedding_album_id', $this->record->id)->find($photoId);
        if ($photo) {
            $photo->update([
                'face_descriptors' => $descriptors,
                'faces_count'      => count($descriptors),
            ]);
        }
    }

    public function getUnindexedPhotos(): array
    {
        return $this->record->photos()
            ->whereNull('face_descriptors')
            ->get()
            ->map(fn ($p) => [
                'id'  => $p->id,
                'url' => $p->full_url,
            ])
            ->toArray();
    }
}