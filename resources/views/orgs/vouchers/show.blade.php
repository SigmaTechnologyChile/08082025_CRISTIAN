@extends('layouts.nice', ['active' => 'orgs.vouchers.show', 'title' => 'Voucher de Orden'])

@section('content')
    <div class="container py-5" style="max-width: 600px;">


        <div class="border p-4 rounded shadow-sm bg-light">
            <div class="text-center mb-4" style="text-align: center;">
                <h4 class="fw-bold text-primary" style="text-align: center;">Comité de Agua Potable Rural Las Arboledas Teno</h4>
                <p class="mb-1 text-muted" style="text-align: center;">RUT: 71.108.700-1</p>
                <p class="mb-1 text-muted" style="text-align: center;">Tel: +569 52534790</p>
                <p class="mb-1 text-muted" style="text-align: center;"><span style="font-size:0.85em !important; letter-spacing:-0.5px;">comiteaprlasarboledas@gmail.com</span></p>
            </div>
            @foreach($orderItems as $item)
                <hr class="my-4">

                <div class="mb-3">
                    <h5 class="fw-bold">Detalle del Pago Folio: {{$item->folio}}</h5>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Pago realizado con:</strong>
                                @php
                                    $metodo = 'Otro';
                                    if ($order->payment_method_id == 1) $metodo = 'POS';
                                    elseif ($order->payment_method_id == 2) $metodo = 'Efectivo';
                                    elseif ($order->payment_method_id == 3) $metodo = 'Transferencia';
                                @endphp
                                <span class="badge bg-success">{{ $metodo }}</span>
                            </p>

                        </div>

                        <div class="col-6">
                            <p><strong>Costo Servicio:</strong> <span
                                    class="fw-bold text-danger">@money($order->commission)</span></p>
                        </div>
                        <div class="col-6 text-end">
                            <p><strong>Total: </strong> <span class="fw-bold text-danger">@money($item->total)</span></p>
                        </div>
                    </div>
                </div>
            @endforeach


              <div class="row mt-4">
    <div class="col-12 text-end">
        <hr>
        <p class="mb-1"><strong style="font-size: 1.2rem;">Total pagado:</strong></p>
        <p class="fw-bold text-danger" style="font-size: 1.5rem;">@money($order->total)</p>
    </div>
</div>

            <hr class="my-4">

            <div class="mb-3">
                <h6 class="fw-bold" style="text-align: center;">Información del Servicio</h6>
                <div class="row" style="text-align: center;">
                    <p class="col-12" style="text-align: center;"><strong>N° de orden:</strong> {{ $order->order_code }}</p>
                    <p class="col-12" style="text-align: center;"><strong>Fecha:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
                    <p class="col-12" style="text-align: center;"><strong>RUT/RUN:</strong> {{ $order->dni }}</p>
                    <p class="col-12" style="text-align: center;"><strong>Cliente:</strong> {{ $order->name }}</p>
                </div>
            </div>

            <hr class="my-4">

            <div class="text-center mb-4" style="text-align: center;">
                <p class="fw-bold text-success" style="text-align: center;">¡Muchas gracias por preferirnos!</p>
                <p class="mb-1" style="text-align: center;">Ante cualquier reclamo o sugerencia, por favor comuníquese a nuestro correo:</p>
                <p style="text-align: center;"><strong style="font-size:0.85em !important; letter-spacing:-0.5px;">comiteaprlasarboledas@gmail.com</strong></p>
                <p style="text-align: center;"><a href="https://www.hydrosite.cl" class="text-decoration-none text-primary">www.hydrosite.cl</a></p>
            </div>

            <div class="text-center">
                <button onclick="window.print()" class="btn btn-success btn-lg"><i class="bi bi-printer"></i>
                    Imprimir</button>
            </div>
        </div>


    </div>
@endsection
<style>
    @media print {
        @page {
            size: 78mm auto;
            margin: 0;
        }

        html, body, .container {
            width: 78mm !important;
            max-width: 78mm !important;
            min-width: 78mm !important;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            height: auto;
            display: block;
        }

        body * {
            visibility: hidden;
        }

        .container,
        .container * {
            visibility: visible;
        }

        .container {
            margin: 0 auto;
            padding: 0;
            position: relative;
            top: 0;
            left: 0;
            transform: none;
            box-shadow: none !important;
            background: white !important;
            page-break-after: avoid;
        }

        h4, h5, h6 {
            font-size: 1.1em !important;
            margin: 0.2em 0 !important;
        }
        p, strong, span, a {
            font-size: 0.95em !important;
            margin: 0.1em 0 !important;
            word-break: break-word !important;
        }
        .fw-bold {
            font-weight: bold !important;
        }
        .text-success, .text-danger, .text-primary, .text-muted {
            font-size: 0.95em !important;
        }

        /* Oculta partes no deseadas */
        header,
        footer,
        nav,
        .navbar,
        .sidebar,
        .btn,
        button {
            display: none !important;
        }
    }
</style>
