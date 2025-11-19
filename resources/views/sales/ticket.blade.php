<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta #{{ $sale->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1a202c; /* Fondo oscuro similar al de la imagen de categorías */
            margin: 0;
            padding: 0;
        }

        .ticket-container {
            max-width: 320px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 12px; /* Esquinas más suaves */
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2); /* Sombra ligeramente más pronunciada en fondo oscuro */
            font-size: 0.9em;
            line-height: 1.5;
            color: #34495e; /* Color de texto más oscuro pero suave para el contenido del ticket */
        }
        
        /* Línea de productos */
        .product-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 0.95em;
        }
        
        .product-qty-price {
            display: flex;
            justify-content: space-between;
            width: 100%;
            font-size: 0.8em;
            color: #7f8c8d;
        }
        
        .ticket-line {
            border-bottom: 1px dashed #dcdde1; /* Línea suave dentro del ticket blanco */
            margin: 15px 0;
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dcdde1; /* Borde sutil */
            position: relative; 
        }
        
        .total-box {
            background-color: #e8f5e9; /* Fondo muy claro para resaltar (verde pastel) */
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #c8e6c9; /* Borde sutil del total */
        }

        .logo {
            max-width: 80px; /* Logo ligeramente más grande */
            height: auto;
            display: block;
            margin: 0 auto;
            border-radius: 4px; /* Opcional: si el logo es cuadrado */
        }
        
        /* Nuevo estilo para el icono de configuración (engranaje) */
        .settings-icon { 
            position: absolute;
            top: 5px;
            right: 5px;
            padding: 5px;
            background-color: transparent; /* Fondo transparente */
            border-radius: 50%;
            cursor: pointer;
            transition: color 0.2s;
        }

        .settings-icon:hover {
            color: #27ae60; /* Color principal al pasar el ratón */
        }

        /* Botón de impresión con un color más corporativo */
        .print-button {
            background-color: #2ecc71; /* Un verde vibrante */
            transition: background-color 0.2s;
        }
        
        .print-button:hover {
            background-color: #27ae60;
        }

        /* Enlace "Volver al listado" con colores adaptados al fondo oscuro */
        .back-link {
            color: #a0aec0; /* Gris claro para el texto en fondo oscuro */
        }
        .back-link:hover {
            color: #cbd5e0; /* Un gris más claro al pasar el ratón */
        }

        /* [ ... Media Print ... ] */
        @media print {
            body {
                font-family: 'Courier New', Courier, monospace !important; 
                background: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .ticket-container {
                max-width: 80mm;
                box-shadow: none !important;
                border: none !important;
                padding: 5px;
                margin: 0;
                font-size: 10px;
                line-height: 1.2;
                color: #000; /* Asegurar negro para impresión */
            }
            
            .settings-icon, .no-print {
                display: none !important;
            }
            
            .logo { 
                max-width: 60px; 
                margin-bottom: 5px;
            }

            h2, p { margin: 0; padding: 0; }
            .ticket-line { margin: 8px 0; border-bottom: 1px dashed #000; }
            .product-qty-price { font-size: 9px; color: #000; } 
            .total-box { background: none !important; padding: 0; border: none !important; }
        }
    </style>
</head>

<body>

    <div class="ticket-container">

        {{-- BOTONES DE ACCIÓN (SOLO EN PANTALLA, NO IMPRIMIR) --}}
        <div class="ticket-header no-print">
            <a href="{{ route('sales.index') }}"
                class="back-link hover:text-gray-300 transition-colors flex items-center justify-center mb-4 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                Volver al listado
            </a>

            <button onclick="window.print()"
                class="w-full print-button text-white font-semibold py-2 px-3 rounded-lg shadow-md no-print mb-4">
                Imprimir Ticket
            </button>
        </div>

        {{-- ENCABEZADO DEL TICKET (CON ICONO DE AJUSTES MEJORADO) --}}
        <div class="logo-container">
            
            {{-- Botón/Enlace de Edición (Engranaje) --}}
            <a href="{{ route('settings.edit') ?? '#' }}" class="settings-icon no-print" title="Editar configuración del negocio">
                {{-- Nuevo SVG: Engranaje para ajustes/configuración --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.222.19 2.809-.646z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </a>
            
            {{-- Logo editable: Añadimos '?v=...' para EVITAR CACHÉ --}}
            <img src="{{ $logo_url ?? asset('images/default_logo.png') }}{{ '?v=' . time() }}" alt="Logo del Negocio" class="logo">
            
            {{-- Nombre del Negocio editable --}}
            <h2 class="text-2xl font-black mt-3 text-gray-800">{{ $nombre_negocio ?? '[NOMBRE NO CONFIGURADO]' }}</h2>
        </div>

        <div class="ticket-header">
            <p class="text-sm text-gray-600">Venta: #{{ $sale->id }}</p>
            <p class="text-sm text-gray-600">Fecha: {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</p>
            <p class="text-sm font-bold mt-2 text-gray-800">Cliente: {{ $sale->customer->name }}</p>
        </div>

        <div class="ticket-line"></div>

        {{-- DETALLE DE PRODUCTOS --}}
        <div class="flex justify-between font-bold mb-3 text-sm text-gray-800">
            <span>DESCRIPCIÓN</span>
            <span class="text-right">IMPORTE</span>
        </div>
        
        @foreach ($sale->saleItems as $item)
        <div class="product-item">
            <span class="font-medium text-gray-700">{{ $item->product->name }}</span>
            <span class="text-right text-gray-700 font-semibold">${{ number_format($item->quantity * $item->price, 2) }}</span>
        </div>
        <div class="product-qty-price pl-2 pb-1">
            <span>{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</span>
        </div>
        @endforeach

        <div class="ticket-line"></div>

        {{-- TOTAL (RESALTADO) --}}
        <div class="total-box flex justify-between font-extrabold text-xl">
            <span>TOTAL:</span>
            <span class="text-green-700">${{ number_format($sale->amount, 2) }}</span>
        </div>

        {{-- NOTAS --}}
        @if($sale->notes)
        <div class="ticket-line"></div>
        <p class="text-xs text-center p-2 bg-gray-50 rounded text-gray-600">
            <span class="font-bold text-gray-800">** NOTAS **</span><br>
            {{ $sale->notes }}
        </p>
        @endif

        <div class="ticket-line"></div>

        {{-- CÓDIGO QR Y FOOTER --}}
        <div class="qr-code-container text-center">
            <div id="qrcode" class="mx-auto my-3" style="width: 80px; height: 80px;"></div>
            <p class="text-xs mt-3 text-gray-700">¡Gracias por tu compra!</p>
            <p class="text-xs text-gray-500">Escanea para más detalles o promociones.</p>
        </div>

        <div class="ticket-line"></div>

        <div class="ticket-footer text-center">
            <p class="text-xs font-semibold text-gray-400">Desarrollado por IslaControl</p>
        </div>

    </div>

    <script>
        // Lógica para generar el Código QR
        document.addEventListener('DOMContentLoaded', () => {
            const qrContent = "https://www.islacontrol.com/sales/{{ $sale->id }}";
            
            new QRCode(document.getElementById("qrcode"), {
                text: qrContent,
                width: 80,
                height: 80,
                colorDark: "#34495e", // Color más suave para el QR
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });
    </script>

</body>

</html>