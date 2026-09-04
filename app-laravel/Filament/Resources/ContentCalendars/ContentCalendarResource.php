<?php

namespace App\Filament\Resources\ContentCalendars;

use App\Filament\Resources\ContentCalendars\Pages\CreateContentCalendar;
use App\Filament\Resources\ContentCalendars\Pages\EditContentCalendar;
use App\Filament\Resources\ContentCalendars\Pages\ListContentCalendars;
use App\Models\BlogPost;
use App\Models\ContentCalendar;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ContentCalendarResource extends Resource
{
    protected static ?string $model = ContentCalendar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static string|UnitEnum|null $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Content Calendar';

    protected static ?string $recordTitleAttribute = 'topic';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Editorial Plan')
                ->description('Plan and schedule future wedding articles and guides.')
                ->columns(2)
                ->schema([
                    TextInput::make('topic')
                        ->label('Article Topic / Title')
                        ->required()
                        ->placeholder('e.g. 5 Iconic Sunset Photo Spots at Taj Lands End')
                        ->columnSpanFull(),

                    TextInput::make('target_keyword')
                        ->label('Target Keyword')
                        ->placeholder('Taj Lands End wedding photography')
                        ->columnSpan(1),

                    Select::make('category')
                        ->label('Category')
                        ->options([
                            'Destination Weddings' => 'Destination Weddings',
                            'Wedding Photography'  => 'Wedding Photography',
                            'Cinematic Films'      => 'Cinematic Films',
                            'Bridal Style'         => 'Bridal Style',
                            'Planning Guides'      => 'Planning Guides',
                        ])
                        ->default('Wedding Photography')
                        ->required()
                        ->columnSpan(1),

                    DatePicker::make('scheduled_for')
                        ->label('Target Publication Date')
                        ->required()
                        ->default(now()->addDays(7))
                        ->columnSpan(1),

                    Select::make('status')
                        ->label('Workflow Status')
                        ->options([
                            'planned'   => 'Planned / Backlog',
                            'generated' => 'Generated Draft',
                            'published' => 'Published Live',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('planned')
                        ->required()
                        ->columnSpan(1),

                    Select::make('blog_post_id')
                        ->label('Linked Blog Post')
                        ->options(BlogPost::pluck('title', 'id'))
                        ->searchable()
                        ->nullable()
                        ->columnSpanFull(),

                    Textarea::make('notes')
                        ->label('Editorial Notes & Angles')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scheduled_for')->label('Date')->date()->sortable()->weight('bold'),
                TextColumn::make('topic')->label('Topic')->searchable()->limit(45),
                TextColumn::make('category')->badge()->color('gray'),
                TextColumn::make('target_keyword')->label('Keyword')->limit(25)->color('info'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'generated' => 'warning',
                        'planned'   => 'info',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),
            ])
            ->recordActions([
                Action::make('write_with_ai')
                    ->label('Write with AI ✨')
                    ->icon('heroicon-o-sparkles')
                    ->url(fn (ContentCalendar $record) => "/admin/ai-blog-writer?topic=" . urlencode($record->topic) . "&target_keyword=" . urlencode($record->target_keyword ?? '')),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListContentCalendars::route('/'),
            'create' => CreateContentCalendar::route('/create'),
            'edit'   => EditContentCalendar::route('/{record}/edit'),
        ];
    }
}
