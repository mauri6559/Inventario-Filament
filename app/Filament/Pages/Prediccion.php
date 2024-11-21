<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class Prediccion extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.prediccion';

    public function mount() {
        $this->form->fill();
    }

    public function form(Form $form):Form{
        return $form->schema([
            Grid::make(2) // Define un grid con 2 columnas
                ->schema([
                    Select::make('name')
                    ->label('Nombre')
                    ->options([
                        'option1' => 'Opción 1',
                        'option2' => 'Opción 2',
                        'option3' => 'Opción 3',
                    ])
                    ->required()
                    ->placeholder('Selecciona una opción'), // Texto de ayuda inicial
    
                    DatePicker::make('start_date')
                        ->label('Fecha de inicio')
                        ->required()
                        ->default(now()),
    
                    TextInput::make('days')
                        ->label('Tiempo en días')
                        ->numeric()
                        ->required()
                        ->default(1)
                        ->minValue(1)
                        ->helperText('Debe ser un número positivo mayor a 0.'),
                ]),
        ]);
    }

    protected function getFormActions(): array
{
    return [
        Action::make('submit')
            ->label('Guardar')
            ->action('submitForm'),
        
    ];
}
}
