<?php

namespace App\Filament\Resources\WeddingAlbums;

use App\Filament\Resources\WeddingAlbums\Pages\CreateWeddingAlbum;
use App\Filament\Resources\WeddingAlbums\Pages\EditWeddingAlbum;
use App\Filament\Resources\WeddingAlbums\Pages\ListWeddingAlbums;
use App\Filament\Resources\WeddingAlbums\Pages\ManageAlbumPhotos;
use App\Models\WeddingAlbum;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WeddingAlbumResource extends Resource
{
    protected static ?string $model = WeddingAlbum::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Client Galleries & AI';

    protected static ?string $navigationLabel = 'Wedding Albums & AI';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('1. Client VIP Delivery Hub (Google Drive Photos & Videos)')
                ->description('Configure the couple\'s private delivery portal, 4-digit access PIN, and Google Drive folder link. Google Drive links remain 100% hidden from clients.')
                ->icon('heroicon-o-cloud-arrow-down')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Wedding / Event Title')
                        ->placeholder('e.g. Aditi & Kabir — Royal Udaipur Wedding')
                        ->required()
                        ->columnSpan(1),

                    TextInput::make('couple_names')
                        ->label('Couple Names')
                        ->placeholder('Aditi & Kabir')
                        ->columnSpan(1),

                    TextInput::make('slug')
                        ->label('Website VIP Link Slug')
                        ->placeholder('aditi-kabir-2026')
                        ->helperText('Client link: paneventz.com/gallery/your-slug')
                        ->columnSpan(1),

                    DatePicker::make('event_date')
                        ->label('Wedding Date')
                        ->columnSpan(1),

                    TextInput::make('location')
                        ->label('Destination / Venue')
                        ->placeholder('Udaipur, Rajasthan')
                        ->columnSpan(1),

                    TextInput::make('pin_code')
                        ->label('Private 4-Digit Passcode (PIN)')
                        ->placeholder('e.g. 2026')
                        ->helperText('Clients will be asked for this PIN to unlock and download their media on your website. Leave blank for public access.')
                        ->maxLength(10)
                        ->columnSpan(1),

                    TextInput::make('google_drive_folder_id')
                        ->label('Google Drive 5TB Folder Link (Photos & Videos)')
                        ->placeholder('https://drive.google.com/drive/folders/1aBcDeFgHiJkLmNoP...')
                        ->helperText('Paste your Google Drive link here. The Google Drive URL will remain 100% hidden from clients—they download directly via Paneventz.')
                        ->columnSpanFull(),

                    FileUpload::make('cover_image')
                        ->label('Album Cover Photo')
                        ->image()
                        ->disk('public')
                        ->directory('albums/covers')
                        ->visibility('public')
                        ->columnSpanFull(),

                    Toggle::make('is_public')
                        ->label('Show on Public Website Directory (/galleries)')
                        ->helperText('Turn OFF if you want this to be 100% PRIVATE & UNLISTED. When OFF, strangers cannot see it on /galleries—only clients with the direct secret VIP link & PIN can unlock it.')
                        ->default(false),

                    Toggle::make('allow_downloads')
                        ->label('Allow Clients & Guests to Download Media')
                        ->default(true),
                ]),

            \Filament\Schemas\Components\Section::make('2. Guest AI Facial Recognition & Banquet QR Code')
                ->description('Upload guest photos from Google Drive so the AI can index faces, allowing guests to snap selfies and find their photos.')
                ->icon('heroicon-o-sparkles')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('guest_google_drive_folder_id')
                        ->label('Guest Photos Google Drive Link (For AI Facial Recognition)')
                        ->placeholder('https://drive.google.com/drive/folders/...')
                        ->helperText('Paste the Google Drive folder containing wedding guest & banquet photos. The AI will scan and index faces so guests can find their photos with a selfie.')
                        ->columnSpanFull(),

                    Toggle::make('enable_face_ai')
                        ->label('Enable AI Face Finder & Table QR Codes for Guests')
                        ->helperText('When enabled, guests can scan banquet table QR codes to snap a selfie and instantly find all their photos.')
                        ->default(true)
                        ->columnSpanFull(),
                ]),

            \App\Filament\Components\SeoFields::make('seo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image')
                    ->disk('public')
                    ->label('Cover')
                    ->circular(),

                TextColumn::make('title')
                    ->label('Wedding Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('couple_names')
                    ->label('Couple')
                    ->searchable(),

                TextColumn::make('pin_code')
                    ->label('Access PIN')
                    ->badge()
                    ->color(fn ($state) => $state ? 'warning' : 'gray')
                    ->formatStateUsing(fn ($state) => $state ? "🔒 {$state}" : '🔓 Public'),

                TextColumn::make('photos_count')
                    ->counts('photos')
                    ->label('Media Files')
                    ->badge()
                    ->color('primary'),

                IconColumn::make('is_public')
                    ->label('Public Directory')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('generate_ai_blog')
                    ->label('AI Blog ✨')
                    ->icon('heroicon-o-sparkles')
                    ->color('warning')
                    ->url(fn (WeddingAlbum $record) => "/admin/ai-blog-writer?album_id={$record->id}"),

                Action::make('client_files')
                    ->label('Drive')
                    ->icon('heroicon-o-cloud-arrow-down')
                    ->color('primary')
                    ->url(fn (WeddingAlbum $record) => static::getUrl('photos', ['record' => $record, 'tab' => 'drive'])),

                Action::make('guest_portal')
                    ->label('VIP ↗')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn (WeddingAlbum $record) => $record->guest_url)
                    ->openUrlInNewTab(),

                EditAction::make(),
                DeleteAction::make()->requiresConfirmation(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListWeddingAlbums::route('/'),
            'create' => CreateWeddingAlbum::route('/create'),
            'edit'   => EditWeddingAlbum::route('/{record}/edit'),
            'photos' => ManageAlbumPhotos::route('/{record}/photos'),
        ];
    }
}
