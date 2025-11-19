<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta #{{ $sale->id }}</title>
    {{-- CDN de Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Estilos base */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Contenedor principal para el color de fondo específico (Navy/Negro Oscuro) */
        .crud-container {
            /* slate-800 oscuro de las otras vistas */
            background-color: #1e293b;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
        }
        
        /* Estilo para el botón de retroceso */
        .back-button {
            color: #9CA3AF;
            transition: color 0.2s;
        }

        .back-button:hover {
            color: #fff;
        }

        /* Estilo para los botones de acción */
        .btn-action {
            transition: all 0.2s ease-in-out;
        }
        .btn-action:hover {
            transform: scale(1.02);
        }
    </style>
</head>

{{-- Fondo muy oscuro para el cuerpo --}}
<body class="bg-gray-900 min-h-screen font-sans">
    <div class="flex items-start justify-center p-8">
        
        {{-- Contenedor del detalle: más ancho (max-w-4xl) y oscuro --}}
        <div class="crud-container p-8 rounded-xl shadow-2xl w-full max-w-4xl">
            
            {{-- Encabezado y Botones de Acción --}}
            <div class="flex items-start justify-between mb-8">
                
                <div class="flex items-center">
                    {{-- Botón de Regreso (Ícono blanco) --}}
                    <a href="{{ route('sales.index') }}" class="back-button mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    {{-- Título --}}
                    <h1 class="text-3xl font-bold text-white flex-grow">Detalle de Venta #{{ $sale->id }}</h1>
                </div>

                <div class="flex space-x-3">
                    {{-- Botón para ver el ticket (que usa la vista ticket que acabas de estilizar) --}}
                    <a href="{{ route('sales.ticket', $sale->id) }}" target="_blank" class="flex items-center bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-green-700 transition-colors btn-action">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm2 0h6v3H7V4zm4 8V9H7v3h4zm-4 4h8v-3H7v3z" clip-rule="evenodd" />
                        </svg>
                        Imprimir Ticket
                    </a>
                    
                    {{-- Botón Editar --}}
                    <a href="{{ route('sales.edit', $sale->id) }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-colors btn-action">
                        Editar Venta
                    </a>
                </div>
            </div>

            <hr class="border-gray-700 mt-8 mb-6">
            
            {{-- Sección de Información General --}}
            <div class="bg-gray-800 p-6 rounded-lg mb-8">
                <h2 class="text-xl font-semibold text-green-400 mb-4 border-b border-gray-700 pb-2">Información de la Venta</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-300">
                    <div>
                        <p class="font-medium text-gray-400">Cliente:</p>
                        <p class="text-white text-lg font-bold">{{ $sale->customer->name }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-400">Fecha de Venta:</p>
                        <p class="text-white text-lg font-bold">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="font-medium text-gray-400">Total Pagado:</p>
                        <p class="text-green-400 text-3xl font-extrabold">${{ number_format($sale->amount, 2) }}</p>
                    </div>
                </div>
            </div>

            {{-- Sección de Productos --}}
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-green-400 mb-4 border-b border-gray-700 pb-2">Productos Vendidos</h2>

                {{-- Encabezado de la Tabla (coherente con las vistas de edición/listado) --}}
                <div class="grid grid-cols-5 gap-3 text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 bg-gray-700 p-3 rounded-t-lg">
                    <div class="col-span-2">Producto</div>
                    <div class="text-right">Cantidad</div>
                    <div class="text-right">Precio Unitario</div>
                    <div class="text-right">Subtotal</div>
                </div>

                {{-- Lista de Productos --}}
                <div class="divide-y divide-gray-700">
                    @foreach ($sale->saleItems as $item)
                        <div class="grid grid-cols-5 gap-3 py-3 text-gray-300 hover:bg-gray-800 transition-colors">
                            <div class="col-span-2 text-white font-medium">{{ $item->product->name }}</div>
                            <div class="text-right">{{ $item->quantity }}</div>
                            <div class="text-right">${{ number_format($item->price, 2) }}</div>
                            <div class="text-right text-yellow-400 font-semibold">${{ number_format($item->quantity * $item->price, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Notas Adicionales --}}
            @if($sale->notes)
            <div class="bg-gray-800 p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-green-400 mb-4 border-b border-gray-700 pb-2">Notas Adicionales</h2>
                <p class="text-gray-300 whitespace-pre-wrap">{{ $sale->notes }}</p>
            </div>
            @endif

        </div>
    </div>

</body>

</html>