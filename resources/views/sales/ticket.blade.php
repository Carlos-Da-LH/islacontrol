<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta #{{ $sale->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            @page {
                size: 80mm auto;
                margin: 0mm;
            }

            body {
                font-family: 'Courier New', Courier, monospace !important;
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .ticket-container {
                max-width: 80mm !important;
                width: 80mm !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 3mm !important;
                margin: 0 !important;
                font-size: 8pt !important;
                line-height: 1.2 !important;
                color: #000 !important;
            }

            .settings-icon, .no-print {
                display: none !important;
            }

            .logo-container {
                text-align: center !important;
                margin-bottom: 2mm !important;
                padding-bottom: 2mm !important;
                border-bottom: 1px dashed #000 !important;
            }

            .logo {
                max-width: 35mm !important;
                max-height: 35mm !important;
                width: auto !important;
                height: auto !important;
                display: block !important;
                margin: 0 auto 3mm auto !important;
            }

            .logo-container h2 {
                font-size: 16pt !important;
                font-weight: bold !important;
                margin: 3mm 0 !important;
                padding: 0 !important;
                line-height: 1.4 !important;
            }

            .logo-container p {
                font-size: 11pt !important;
                margin: 1.5mm 0 !important;
                padding: 0 !important;
                line-height: 1.4 !important;
            }

            .ticket-header {
                margin: 3mm 0 !important;
            }

            .ticket-header p {
                font-size: 11pt !important;
                margin: 1mm 0 !important;
                padding: 0 !important;
            }

            h2 {
                margin: 3mm 0 !important;
                padding: 0 !important;
            }

            p {
                margin: 1.5mm 0 !important;
                padding: 0 !important;
            }

            .ticket-line {
                margin: 3mm 0 !important;
                border-bottom: 1px dashed #000 !important;
                height: 0 !important;
            }

            .flex {
                display: flex !important;
                justify-content: space-between !important;
            }

            .product-item {
                font-size: 11pt !important;
                margin-bottom: 1.5mm !important;
            }

            .product-qty-price {
                font-size: 10pt !important;
                color: #000 !important;
                margin: 0 0 2.5mm 0 !important;
                padding-left: 4mm !important;
            }

            .total-box {
                font-size: 14pt !important;
                font-weight: bold !important;
                background: #f0f0f0 !important;
                padding: 3mm !important;
                margin: 3mm 0 !important;
                border: 1px solid #000 !important;
                border-radius: 0 !important;
            }

            .qr-code-container {
                text-align: center !important;
                margin: 4mm 0 !important;
            }

            .qr-code-container p {
                font-size: 11pt !important;
                margin: 1.5mm 0 !important;
            }

            #qrcode {
                width: 70px !important;
                height: 70px !important;
                margin: 3mm auto !important;
                display: block !important;
            }

            #qrcode img,
            #qrcode canvas {
                width: 70px !important;
                height: 70px !important;
            }

            .ticket-footer {
                text-align: center !important;
                margin-top: 3mm !important;
            }

            .ticket-footer p {
                font-size: 9pt !important;
                margin: 0 !important;
                color: #666 !important;
            }
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

            {{-- DEBUG: Deshabilitado --}}
            {{-- <div style="background:yellow;padding:10px;margin:10px 0;" class="no-print">
                <strong>DEBUG:</strong><br>
                Teléfono isset: {{ isset($telefono) ? 'SI' : 'NO' }}<br>
                Teléfono valor: '{{ $telefono ?? 'NO DEFINIDO' }}'<br>
                Teléfono empty: {{ empty($telefono ?? '') ? 'SI' : 'NO' }}<br>
                Ubicación isset: {{ isset($ubicacion) ? 'SI' : 'NO' }}<br>
                Ubicación valor: '{{ $ubicacion ?? 'NO DEFINIDO' }}'<br>
                Ubicación empty: {{ empty($ubicacion ?? '') ? 'SI' : 'NO' }}
            </div> --}}

            {{-- Teléfono del negocio --}}
            @if(isset($telefono) && $telefono != '')
            <p class="text-sm text-gray-600 mt-2 flex items-center justify-center gap-1">
                <i class='bx bx-phone'></i>
                <span>{{ $telefono }}</span>
            </p>
            @endif

            {{-- Ubicación del negocio --}}
            @if(isset($ubicacion) && $ubicacion != '')
            <p class="text-xs text-gray-600 mt-1 leading-tight px-2 text-center">
                <i class='bx bx-map'></i>
                <span>{{ $ubicacion }}</span>
            </p>
            @endif
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

        {{-- MÉTODO DE PAGO --}}
        @if($sale->payment_method)
        <div class="ticket-line"></div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-sm font-bold text-gray-800 mb-2">
                <i class='bx bx-money'></i> Método de Pago:
            </p>
            <p class="text-base font-semibold text-gray-700">
                @if($sale->payment_method == 'efectivo')
                    💵 Efectivo
                @elseif($sale->payment_method == 'tarjeta_debito')
                    💳 Tarjeta de Débito
                @elseif($sale->payment_method == 'tarjeta_credito')
                    💳 Tarjeta de Crédito
                @elseif($sale->payment_method == 'transferencia')
                    🏦 Transferencia
                @else
                    {{ ucfirst($sale->payment_method) }}
                @endif
            </p>

            {{-- Mostrar detalles de efectivo --}}
            @if($sale->payment_method == 'efectivo' && $sale->amount_received)
            <div class="mt-2 text-sm text-gray-600 space-y-1">
                <div class="flex justify-between">
                    <span>Recibido:</span>
                    <span class="font-semibold">${{ number_format($sale->amount_received, 2) }}</span>
                </div>
                @if($sale->change_returned)
                <div class="flex justify-between text-yellow-700 font-bold">
                    <span>Cambio:</span>
                    <span>${{ number_format($sale->change_returned, 2) }}</span>
                </div>
                @endif
            </div>
            @endif

            {{-- Mostrar referencia para tarjetas/transferencias --}}
            @if($sale->payment_reference && $sale->payment_method != 'efectivo')
            <div class="mt-2 text-xs text-gray-600">
                <span class="font-semibold">Ref:</span> {{ $sale->payment_reference }}
            </div>
            @endif
        </div>
        @endif

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
            <div id="qrcode" class="mx-auto my-3" style="width: 70px; height: 70px;"></div>
            <p class="text-xs mt-3 text-gray-700">¡Gracias por tu compra!</p>
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
                width: 70,
                height: 70,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.M
            });
        });
    </script>

</body>

</html>