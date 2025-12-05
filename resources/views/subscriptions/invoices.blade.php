<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Facturas - IslaControl</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #00D084 0%, #00B372 100%);
        }

        .invoice-row {
            transition: all 0.2s ease;
        }

        .invoice-row:hover {
            background-color: #f9fafb;
            transform: translateX(4px);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <div class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('subscription.dashboard') }}" class="flex items-center gap-2 text-emerald-600 hover:text-emerald-700">
                        <i class='bx bx-arrow-back text-xl'></i>
                        <span class="font-semibold">Volver a Mi Suscripción</span>
                    </a>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Mis Facturas</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">

        <!-- Summary Cards -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total de facturas</p>
                        <p class="text-3xl font-bold text-gray-800">{{ count($invoices) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class='bx bx-receipt text-blue-600 text-2xl'></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total pagado</p>
                        <p class="text-3xl font-bold text-emerald-600">
                            ${{ number_format(collect($invoices)->where('status', 'paid')->sum('total') / 100, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                        <i class='bx bx-dollar-circle text-emerald-600 text-2xl'></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Última factura</p>
                        <p class="text-lg font-bold text-gray-800">
                            @if(count($invoices) > 0)
                                {{ $invoices[0]->date()->format('d/m/Y') }}
                            @else
                                Sin facturas
                            @endif
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class='bx bx-calendar text-purple-600 text-2xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices List -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Historial de Facturas</h2>
            </div>

            @if(count($invoices) > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Número de Factura
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Monto
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Acción
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($invoices as $invoice)
                        <tr class="invoice-row">
                            <!-- Date -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i class='bx bx-calendar text-gray-400'></i>
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $invoice->date()->format('d/m/Y') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Invoice Number -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600 font-mono">
                                    {{ $invoice->number }}
                                </span>
                            </td>

                            <!-- Description -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    @foreach($invoice->lines->data as $line)
                                        {{ $line->description }}
                                        @if(!$loop->last), @endif
                                    @endforeach
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($invoice->status === 'paid')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <i class='bx bx-check-circle'></i>
                                    Pagada
                                </span>
                                @elseif($invoice->status === 'open')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    <i class='bx bx-time-five'></i>
                                    Pendiente
                                </span>
                                @elseif($invoice->status === 'void')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                    <i class='bx bx-x-circle'></i>
                                    Anulada
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    <i class='bx bx-error'></i>
                                    {{ ucfirst($invoice->status) }}
                                </span>
                                @endif
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900">
                                    ${{ number_format($invoice->total / 100, 2) }}
                                </span>
                                <span class="text-xs text-gray-500 uppercase">
                                    {{ $invoice->currency }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- View Invoice Online -->
                                    <a href="{{ $invoice->invoice_pdf }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-semibold">
                                        <i class='bx bx-show'></i>
                                        Ver
                                    </a>

                                    <!-- Download PDF -->
                                    <a href="{{ route('subscription.invoice.download', $invoice->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-2 gradient-bg text-white rounded-lg hover:shadow-lg transition text-sm font-semibold">
                                        <i class='bx bx-download'></i>
                                        Descargar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <!-- Empty State -->
            <div class="px-6 py-12 text-center">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-receipt text-gray-400 text-5xl'></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">No tienes facturas aún</h3>
                <p class="text-gray-600 mb-6">Tus facturas aparecerán aquí cuando realices pagos</p>
                <a href="{{ route('subscription.dashboard') }}" class="inline-flex items-center gap-2 gradient-bg text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition">
                    <i class='bx bx-arrow-back'></i>
                    Volver a Mi Suscripción
                </a>
            </div>
            @endif
        </div>

        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class='bx bx-help-circle text-blue-600 text-2xl'></i>
                </div>
                <div>
                    <h3 class="font-bold text-blue-900 mb-2">¿Necesitas ayuda con tus facturas?</h3>
                    <p class="text-sm text-blue-800 mb-3">
                        Todas tus facturas se generan automáticamente después de cada pago.
                        Puedes descargarlas en formato PDF o verlas en línea en cualquier momento.
                    </p>
                    <div class="space-y-1 text-sm text-blue-800">
                        <p>• Las facturas incluyen todos los detalles de tu suscripción</p>
                        <p>• Puedes usar estas facturas para tu contabilidad</p>
                        <p>• Los pagos se procesan de forma segura a través de Stripe</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
