<?php

namespace App\Filament\Pages;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Prediccion extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.prediccion';

    public $producto;
    public $fecha_inicio;
    public $dias_futuro;
    public $imageBase64 = null;
    public $tablaPredicciones = [];

    public function mount() {
        $this->form->fill();
    }

    public function form(Form $form):Form{
        return $form->schema([
            Grid::make(2) // Define un grid con 2 columnas
                ->schema([
                    Select::make('producto')
                    ->label('Nombre')
                    ->options([
                        'S18_2238' => 'S18_2238',
                        'S18_3136' => 'S18_3136',
                        'S12_1666' => 'S12_1666',
                        'S18_1097' => 'S18_1097',
                        'S24_3420' => 'S24_3420',
                        'S10_1949' => 'S10_1949',
                        'S24_1444' => 'S24_1444',
                        'S18_3232' => 'S18_3232',
                        'S18_4600' => 'S18_4600',
                        'S50_1392' => 'S50_1392',
                        'S10_4962' => 'S10_4962',
                        'S18_2432' => 'S18_2432',
                        'S32_2509' => 'S32_2509',
                        'S24_3856' => 'S24_3856',
                        'S24_3949' => 'S24_3949',
                        'S24_2972' => 'S24_2972',
                        'S32_1268' => 'S32_1268',
                        'S18_2957' => 'S18_2957',
                        'S18_2949' => 'S18_2949',
                        'S24_2840' => 'S24_2840',

                    ])
                    ->required()
                    ->placeholder('Selecciona una opción'), // Texto de ayuda inicial
    
                    DatePicker::make('fecha_inicio')
                        ->label('Fecha de inicio')
                        ->required()
                        ->default(now()->format('Y-m-d')),
    
                    TextInput::make('dias_futuro')
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
                ->label('Generar Predicción')
                ->action('submitForm'),
            Action::make('exportPdf')
                ->label('Reporte PDF')
                ->action('exportPdf')
                ->visible(fn () => !empty($this->tablaPredicciones)),
        ];
    }

    public function submitForm()
    {
        // Obtén los datos ingresados por el usuario
        $formData = $this->form->getState(); 

        try {
            // Realiza la solicitud POST a la API
            $response = Http::post('http://192.168.100.102:5000/predict', [
                'producto' => $formData['producto'],       // Campo "producto" del formulario
                'fecha_inicio' => $formData['fecha_inicio'], // Campo "fecha_inicio" del formulario
                'dias_futuro' => $formData['dias_futuro'],              // Campo "dias" del formulario
            ]);

            // Verifica si la respuesta fue exitosa
            if ($response->successful()) {
                // Maneja el éxito, por ejemplo, muestra una notificación

                // Si la API devuelve una imagen en base64, puedes manejarla aquí
                if ($imageBase64 = $response->json('grafico_linea')) {
                    $this->imageBase64 = $imageBase64; // Guarda la imagen para mostrarla en la vista
                }
                if ($tablaPredicciones = $response->json('tabla')) {
                    $this->tablaPredicciones = $tablaPredicciones;
                }
            } else {
                // Maneja errores de la API
                Log::info('Error en la API: ' . $response->body());
            }
        } catch (\Exception $e) {
            // Maneja errores de conexión u otros problemas
            Log::info('Error al conectar con la API: ' . $e->getMessage());
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('Fecha')
                    ->label('Fecha'),
                TextColumn::make('Predicción Ajustada (Cantidad)')
                    ->label('Cantidad')
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
            ])
            ->records($this->tablaPredicciones) // Usa los datos de las predicciones
            ->pagination(10); // Número de elementos por página
    }

    public function exportPdf()
    {
        // Verifica si hay datos para exportar
        if (empty($this->tablaPredicciones)) {
            $this->notify('danger', 'No hay datos para exportar.');
            return;
        }

        // Datos para la vista del PDF
        $data = [
            'tablaPredicciones' => $this->tablaPredicciones,
            'imageBase64' => $this->imageBase64,
        ];

        // Genera el PDF desde una vista
        $pdf = Pdf::loadView('exports.prediccion_pdf', $data);

        // Devuelve el archivo como descarga
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'reporte_prediccion.pdf'
        );
    }

    
}
