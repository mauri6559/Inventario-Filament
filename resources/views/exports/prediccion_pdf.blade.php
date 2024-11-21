<!DOCTYPE html>
<html>
<head>
    <title style="text-align: center">Reporte de Predicción</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .grafica { margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <h1>Reporte de Predicción</h1>

    

    <h2>Tabla de Predicciones</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Predicción Ajustada (Cantidad)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tablaPredicciones as $prediccion)
                <tr>
                    <td>{{ $prediccion['Fecha'] }}</td>
                    <td>{{ number_format($prediccion['Predicción Ajustada (Cantidad)'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if (!empty($imageBase64))
    <div class="grafica">
        <h2>Gráfica Generada</h2>
        <img src="data:image/png;base64,{{ $imageBase64 }}" alt="Gráfica" style="width: 80%; max-width: 600px; height: auto;">
    </div>
    @endif
</body>
</html>