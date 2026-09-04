<?php

namespace App\Filament\Pages;

use App\Models\TermsAndCondition;
use App\Services\TermsService;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use UnitEnum;
use BackedEnum;

class ManageTermsAndConditions extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static UnitEnum|string|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Terms & Conditions';

    protected static ?int $navigationSort = 100;

    protected static ?string $title = 'Client Terms & Conditions';

    protected string $view = 'filament-panels::pages.page';

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $terms = TermsAndCondition::current();

        $this->form->fill([
            'version'                   => $terms->version,
            'advance_percentage'        => $terms->advance_percentage,
            'balance_percentage'        => $terms->balance_percentage,
            'balance_due'               => $terms->balance_due,
            'advance_refundable'        => (bool) $terms->advance_refundable,
            'cancellation_policy'       => $terms->cancellation_policy,
            'estimated_delivery_period' => $terms->estimated_delivery_period,
            'delivery_policy'           => $terms->delivery_policy,
            'extra_pendrive'            => $terms->extra_pendrive,
            'extended_coverage_after'   => $terms->extended_coverage_after,
            'late_night_transportation' => $terms->late_night_transportation,
            'hotel_coverage'            => $terms->hotel_coverage,
            'home_coverage'             => $terms->home_coverage,
            'extra_hours'               => $terms->extra_hours,
            'content'                   => $terms->content,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                // 1. BOOKING & PAYMENT
                Section::make('1. Booking & Payment Terms')
                    ->description('Set down-payment and final settlement terms for event confirmations.')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('advance_percentage')
                                ->label('Advance Payment (%)')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->live(debounce: 500)
                                ->afterStateUpdated(function ($state, callable $set) {
                                    $adv = (int) $state;
                                    if ($adv >= 0 && $adv <= 100) {
                                        $set('balance_percentage', 100 - $adv);
                                    }
                                })
                                ->helperText('Percentage required to secure the booking date.'),

                            TextInput::make('balance_percentage')
                                ->label('Remaining Balance (%)')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->required()
                                ->helperText('Advance + Balance must equal 100%.'),

                            TextInput::make('balance_due')
                                ->label('Balance Due Timeline')
                                ->required()
                                ->placeholder('e.g. Event Date')
                                ->helperText('When the final balance must be cleared.'),
                        ]),
                    ]),

                // 2. CANCELLATION & REFUND
                Section::make('2. Cancellation & Refund Policy')
                    ->description('Define non-refundable conditions and date rescheduling guidelines.')
                    ->icon('heroicon-o-x-circle')
                    ->schema([
                        Toggle::make('advance_refundable')
                            ->label('Is the advance payment refundable?')
                            ->helperText('Default is No (advance covers scheduling, team reservations, and opportunity costs).')
                            ->default(false),

                        Textarea::make('cancellation_policy')
                            ->label('Cancellation & Rescheduling Policy')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull(),
                    ]),

                // 3. DELIVERY & PHYSICAL MEDIA
                Section::make('3. Delivery & Deliverables Policy')
                    ->description('Set post-production turnaround times and extra pendrive / copy policies.')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('estimated_delivery_period')
                                ->label('Estimated Delivery Period')
                                ->required()
                                ->placeholder('e.g. 1–2 months')
                                ->helperText('Timeline for edited photos and cinematic films.'),

                            TextInput::make('extra_pendrive')
                                ->label('Additional Pendrives / Storage')
                                ->required()
                                ->placeholder('e.g. Chargeable')
                                ->helperText('Policy on extra physical copies requested by client.'),
                        ]),

                        Textarea::make('delivery_policy')
                            ->label('Delivery Variation Factors')
                            ->rows(3)
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Explaining why delivery timelines might vary based on event size or client selections.'),
                    ]),

                // 4. ADDITIONAL CHARGES & SURCHARGES
                Section::make('4. Additional Charges & Surcharges')
                    ->description('Specific terms for late-night overruns, travel, and non-package locations.')
                    ->icon('heroicon-o-plus-circle')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('extended_coverage_after')
                                ->label('Late-Night Cut-off Time')
                                ->required()
                                ->placeholder('e.g. 12:30 AM')
                                ->helperText('Coverage extending past this time incurs surcharges.'),

                            TextInput::make('late_night_transportation')
                                ->label('Late-Night Transportation')
                                ->required()
                                ->placeholder('e.g. Chargeable')
                                ->helperText('Payable if public transport is unavailable.'),

                            TextInput::make('extra_hours')
                                ->label('Extra / Overtime Hours')
                                ->required()
                                ->placeholder('e.g. Chargeable')
                                ->helperText('Hours exceeding package duration.'),

                            TextInput::make('hotel_coverage')
                                ->label('Hotel / Outside Venue Coverage')
                                ->required()
                                ->placeholder('e.g. Additional')
                                ->helperText('Shoots at auxiliary hotels outside schedule.'),

                            TextInput::make('home_coverage')
                                ->label('Home / Residence Coverage')
                                ->required()
                                ->placeholder('e.g. Additional')
                                ->helperText('Shoots at private homes/residences.'),

                            TextInput::make('version')
                                ->label('Active Version #')
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Increments automatically when terms are updated.'),
                        ]),
                    ]),

                // 5. COMPLETE LEGAL CONTRACT WORDING
                Section::make('5. Complete Legal Terms & Agreement')
                    ->description('The full contract clause text provided to clients and included in proposals / quotations.')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        Textarea::make('content')
                            ->label('Full Terms & Conditions Text')
                            ->rows(16)
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Edit any clause or clause formatting. This legal text is snapshotted to quotations and contracts.'),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    EmbeddedSchema::make('form'),
                ])
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make($this->getFormActions()),
                ]),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->icon('heroicon-o-check')
                ->submit('save')
                ->color('primary')
                ->keyBindings(['mod+s']),

            Action::make('preview')
                ->label('Preview Terms')
                ->icon('heroicon-o-eye')
                ->color('gray')
                ->modalHeading('Terms & Conditions Client Preview')
                ->modalDescription('This is how your terms and clauses are formatted for clients and quotations.')
                ->modalContent(fn () => view('filament.pages.terms-preview-modal', [
                    'terms' => (object) array_merge(
                        $this->form->getState(),
                        ['version' => TermsAndCondition::current()->version]
                    ),
                ]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close Preview'),

            Action::make('restoreDefaults')
                ->label('Restore Default Terms')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Restore Default Terms & Conditions?')
                ->modalDescription('Are you sure you want to reset all terms, percentages, and clause wording to standard studio defaults? Any custom modifications will be replaced.')
                ->modalSubmitActionLabel('Yes, Reset to Defaults')
                ->action(fn () => $this->resetToDefaults()),
        ];
    }

    public function save(): void
    {
        $state = $this->form->getState();

        // Strict percentage sum validation
        $advance = (int) ($state['advance_percentage'] ?? 0);
        $balance = (int) ($state['balance_percentage'] ?? 0);

        if (($advance + $balance) !== 100) {
            Notification::make()
                ->title('Invalid Payment Percentages')
                ->body("Advance payment ({$advance}%) and balance payment ({$balance}%) must add up to exactly 100%. (Current total: " . ($advance + $balance) . '%)')
                ->danger()
                ->persistent()
                ->send();

            return;
        }

        $terms = TermsAndCondition::current();

        $terms->update([
            'version'                   => ($terms->version ?? 0) + 1,
            'advance_percentage'        => $advance,
            'balance_percentage'        => $balance,
            'balance_due'               => $state['balance_due'],
            'advance_refundable'        => (bool) ($state['advance_refundable'] ?? false),
            'cancellation_policy'       => $state['cancellation_policy'],
            'estimated_delivery_period' => $state['estimated_delivery_period'],
            'delivery_policy'           => $state['delivery_policy'],
            'extra_pendrive'            => $state['extra_pendrive'],
            'extended_coverage_after'   => $state['extended_coverage_after'],
            'late_night_transportation' => $state['late_night_transportation'],
            'hotel_coverage'            => $state['hotel_coverage'],
            'home_coverage'             => $state['home_coverage'],
            'extra_hours'               => $state['extra_hours'],
            'content'                   => $state['content'],
        ]);

        $this->fillForm();

        Notification::make()
            ->title('Terms & Conditions Updated')
            ->body("Terms version v{$terms->version} has been saved successfully.")
            ->success()
            ->send();
    }

    public function resetToDefaults(): void
    {
        $defaults = TermsAndCondition::defaultAttributes();
        $terms = TermsAndCondition::current();

        $defaults['version'] = ($terms->version ?? 0) + 1;
        $terms->update($defaults);

        $this->fillForm();

        Notification::make()
            ->title('Terms Restored')
            ->body('Default terms, percentages, and standard legal clauses have been restored.')
            ->warning()
            ->send();
    }
}
