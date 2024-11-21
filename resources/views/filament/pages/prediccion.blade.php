<x-filament-panels::page>
    <x-filament-panels::form>
        {{$this->form}}
        <x-filament-panels::form.actions :actions="$this->getFormActions()" />
        @if ($this->imageBase64)
            <div class="mt-4">
                <h2 class="text-lg font-bold">Resultado:</h2>
                <img src="data:image/png;base64,{{ $this->imageBase64 }}" alt="Predicción generada" class="mt-2 rounded shadow">
            </div>
        @endif
        @if ($this->tablaPredicciones)
            <div class="mt-6">
                <h2 class="text-lg font-bold">Tabla de Predicciones:</h2>
                <table class="table-auto w-full border border-gray-200 mt-4">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Fecha</th>
                            <th class="border border-gray-300 px-4 py-2">Predicción Ajustada (Cantidad)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->tablaPredicciones as $prediccion)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $prediccion['Fecha'] }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ number_format($prediccion['Predicción Ajustada (Cantidad)'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-filament-panels::form.actions>

</x-filament-panels::page>
