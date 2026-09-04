<?php

namespace App\Filament\Resources\BlogPosts;

use App\Filament\Components\SeoFields;
use App\Filament\Resources\BlogPosts\Pages\CreateBlogPost;
use App\Filament\Resources\BlogPosts\Pages\EditBlogPost;
use App\Filament\Resources\BlogPosts\Pages\ListBlogPosts;
use App\Models\BlogPost;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Journal & Articles';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Article Content & Workflow')
                ->description('Craft editorial wedding stories, guides, and photography advice.')
                ->icon('heroicon-o-pencil-square')
                ->columns(2)
                ->schema([
                    TextInput::make('title')
                        ->label('Article Title')
                        ->required()
                        ->placeholder('e.g. 10 Breathtaking Heritage Palace Venues in Rajasthan')
                        ->columnSpan(1),

                    TextInput::make('slug')
                        ->label('URL Slug')
                        ->placeholder('heritage-palace-wedding-venues-rajasthan')
                        ->helperText('Public link: /blog/your-slug')
                        ->columnSpan(1),

                    Select::make('category')
                        ->label('Article Category')
                        ->options([
                            'Destination Weddings' => 'Destination Weddings',
                            'Wedding Photography'  => 'Wedding Photography',
                            'Cinematic Films'      => 'Cinematic Films',
                            'Bridal Style'         => 'Bridal Style',
                            'Planning Guides'      => 'Planning Guides',
                            'Heirloom Albums'      => 'Heirloom Albums',
                        ])
                        ->default('Wedding Photography')
                        ->required()
                        ->columnSpan(1),

                    Select::make('status')
                        ->label('Editorial Status')
                        ->options([
                            'draft'        => 'Draft',
                            'ai_generated' => 'AI Generated',
                            'under_review' => 'Under Review',
                            'published'    => 'Published',
                            'archived'     => 'Archived',
                        ])
                        ->default('draft')
                        ->required()
                        ->columnSpan(1),

                    TextInput::make('focus_keyword')
                        ->label('Focus Target Keyword')
                        ->placeholder('e.g. Taj Lands End wedding photography')
                        ->columnSpan(1),

                    TextInput::make('author_name')
                        ->label('Author / Byline')
                        ->default('Paneventz Editorial')
                        ->columnSpan(1),

                    TextInput::make('read_time_minutes')
                        ->label('Estimated Read Time (Minutes)')
                        ->numeric()
                        ->default(5)
                        ->columnSpan(1),

                    DateTimePicker::make('published_at')
                        ->label('Publication Date')
                        ->default(now())
                        ->columnSpan(1),

                    FileUpload::make('featured_image')
                        ->label('Featured Hero Image')
                        ->image()
                        ->disk('public')
                        ->directory('blog')
                        ->visibility('public')
                        ->columnSpanFull(),

                    Textarea::make('excerpt')
                        ->label('Short Summary / Excerpt')
                        ->rows(2)
                        ->placeholder('A concise introductory teaser displayed in the journal directory.')
                        ->columnSpanFull(),

                    Textarea::make('content')
                        ->label('Full Article Body')
                        ->rows(12)
                        ->required()
                        ->placeholder('Write your comprehensive wedding advice or event story here...')
                        ->columnSpanFull(),

                    Toggle::make('is_published')
                        ->label('Published on Website (Publicly Visible)')
                        ->default(true),
                ]),

            SeoFields::make('seo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')->label('Cover')->disk('public')->circular(),
                TextColumn::make('title')->label('Title')->searchable()->sortable()->weight('bold')->limit(45),
                TextColumn::make('category')->badge()->color('primary'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'published'    => 'success',
                        'ai_generated' => 'warning',
                        'under_review' => 'info',
                        'archived'     => 'danger',
                        default        => 'gray',
                    }),
                TextColumn::make('quality_score')
                    ->label('Quality')
                    ->badge()
                    ->color(fn (?int $state): string => $state >= 80 ? 'success' : ($state >= 60 ? 'warning' : 'danger'))
                    ->formatStateUsing(fn (?int $state): string => $state ? "{$state}%" : '—'),
                TextColumn::make('published_at')->label('Date')->date()->sortable(),
                IconColumn::make('is_published')->label('Live')->boolean(),
            ])
            ->recordActions([
                Action::make('view_article')
                    ->label('View ↗')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (BlogPost $record) => $record->url)
                    ->openUrlInNewTab(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListBlogPosts::route('/'),
            'create' => CreateBlogPost::route('/create'),
            'edit'   => EditBlogPost::route('/{record}/edit'),
        ];
    }
}
