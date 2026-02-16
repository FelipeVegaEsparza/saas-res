<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Códigos QR - {{ $restaurant->name }}</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 20px;
        }

        .qr-item {
            border: 2px solid #333;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            background: white;
        }

        .qr-item h2 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .qr-item img {
            max-width: 250px;
            margin: 20px 0;
        }

        .qr-item p {
            margin: 10px 0;
            color: #666;
        }

        .restaurant-name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #696cff;
        }

        .instructions {
            background: #f5f5f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 30px;
            background: #696cff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background: #5f61e6;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">
        🖨️ Imprimir Todos
    </button>

    <div class="no-print" style="text-align: center; margin-bottom: 30px;">
        <h1>Códigos QR - {{ $restaurant->name }}</h1>
        <p>Imprime esta página para tener todos los códigos QR de tus mesas</p>
    </div>

    <div class="qr-grid">
        @foreach($tablesWithQR as $item)
            <div class="qr-item {{ $loop->iteration % 4 == 0 ? 'page-break' : '' }}">
                <h2>{{ $item['table']->number }}</h2>
                <p class="restaurant-name">{{ $restaurant->name }}</p>

                <img src="{{ $item['qrUrl'] }}" alt="QR {{ $item['table']->number }}">

                <div class="instructions">
                    <strong>📱 Escanea para ver el menú</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.8rem;">
                        {{ $item['table']->location ?? 'Interior' }} •
                        Capacidad: {{ $item['table']->capacity }} personas
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="no-print" style="text-align: center; margin-top: 40px;">
        <button onclick="window.print()" style="padding: 15px 40px; font-size: 1.1rem; background: #696cff; color: white; border: none; border-radius: 8px; cursor: pointer;">
            🖨️ Imprimir Todos los QR
        </button>
        <br><br>
        <a href="{{ route('tenant.dashboard') }}" style="color: #696cff; text-decoration: none;">
            ← Volver al Dashboard
        </a>
    </div>
</body>
</html>
