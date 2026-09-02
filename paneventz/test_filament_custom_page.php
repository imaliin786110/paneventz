<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Schemas\Components\Actions;

class ManageTermsTestPage extends Page {
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'advance_percentage' => 50,
            'balance_percentage' => 50,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                TextInput::make('advance_percentage')
                    ->label('Advance %')
                    ->numeric()
                    ->required(),
                TextInput::make('balance_percentage')
                    ->label('Balance %')
                    ->numeric()
                    ->required(),
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
                    Actions::make([
                        Action::make('save')
                            ->label('Save Changes')
                            ->submit('save'),
                    ]),
                ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();
        echo "Valid state: " . json_encode($state) . PHP_EOL;
    }
}

// Test livewire simulation
$component = app(ManageTermsTestPage::class);
$component->boot();
$component->mount();
echo "Data after mount: " . json_encode($component->data) . PHP_EOL;
$component->save();
