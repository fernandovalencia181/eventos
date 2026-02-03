<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Entrada - {{ $registration->event->nombre }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            margin: 0;
            padding: 40px;
        }
        .page-break {
            page-break-after: always;
        }
        .ticket-container {
            max-width: 700px;
            margin: 0 auto 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #0a1d30; /* Primary 900 */
            color: #ffffff;
            padding: 30px 40px;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header .brand {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 5px;
            color: #a5c3e1; /* Primary 300 */
        }
        .body {
            padding: 40px;
        }
        .info-row {
            margin-bottom: 25px;
        }
        .label {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            color: #6b7280; /* Gray 500 */
            margin-bottom: 4px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .value {
            font-size: 18px;
            color: #111827; /* Gray 900 */
            font-weight: 500;
        }
        .value-large {
            font-size: 24px;
            font-weight: 700;
            color: #2d75b9; /* Primary 500 */
        }
        .qr-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
            border: 1px dashed #d1d5db;
        }
        .qr-section img {
            display: block;
            margin: 0 auto;
            border: 8px solid #ffffff;
        }
        .qr-caption {
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
            color: #374151;
        }
        .details-table {
            width: 100%;
            border-spacing: 0;
            margin-top: 20px;
        }
        .details-table td {
            vertical-align: top;
            padding-bottom: 15px;
        }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            background-color: #e1ebf5; /* Primary 100 */
            color: #1b466f; /* Primary 700 */
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 40px;
            border-top: 1px solid #e5e7eb;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
            line-height: 1.5;
        }
        .cut-line {
            border-top: 1px dashed #9ca3af;
            margin: 40px 0;
            position: relative;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(0, 0, 0, 0.03);
            text-transform: uppercase;
            font-weight: bold;
            z-index: 0;
            pointer-events: none;
        }
        .content-relative {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>

    @php
        // Helper para renderizar una entrada
        function renderTicket($title, $name, $subtitle, $evento, $qrToken, $qrImage, $footerId) {
    @endphp
    
    <div class="ticket-container">
        <div class="header">
            <div class="brand">Eventos La Salle</div>
            <h1>{{ $evento->nombre }}</h1>
        </div>
        
        <div class="body content-relative">
            <div class="watermark">ENTRADA</div>
            
            <div class="qr-section">
                <!-- Mostramos QR (prioridad imagen base64, fallback api) -->
                <img src="{{ $qrImage ?? 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data='.$qrToken }}" alt="Código QR" width="180">
                <div class="qr-caption" style="margin-top: 10px; letter-spacing: 2px;">{{ substr($qrToken, 0, 8) }}...</div>
                <div style="font-size: 10px; color: #6b7280; margin-top: 5px;">Muestra este código en la entrada</div>
            </div>

            <table class="details-table">
                <tr>
                    <td style="width: 60%">
                        <div class="info-row">
                            <span class="label">Asistente</span>
                            <div class="value-large">{{ $name }}</div>
                            <div style="margin-top: 5px;">
                                <span class="badge">{{ $title }}</span>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <span class="label">Detalle</span>
                            <div class="value">{{ $subtitle }}</div>
                        </div>
                    </td>
                    <td style="width: 40%">
                        <div class="info-row">
                            <span class="label">Fecha</span>
                            <div class="value">{{ \Carbon\Carbon::parse($evento->fecha)->format('d/m/Y') }}</div>
                        </div>
                        
                        <div class="info-row">
                            <span class="label">Lugar</span>
                            <div class="value">{{ $evento->lugar }}</div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 2px;">{{ $evento->ubicacion ?? 'Recinto Principal' }}</div>
                        </div>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed #e5e7eb;">
                 <p style="font-size: 12px; color: #4b5563;">
                    <strong>Importante:</strong> Esta entrada es válida para un solo acceso y es nominal.
                    El uso del PDF es para el primer acceso. Para salir y re-entrar, utiliza el QR Dinámico en nuestra web.
                 </p>
            </div>
        </div>

        <div class="footer">
            Entrada emitida el {{ now()->format('d/m/Y H:i') }} • ID Único: {{ $footerId }} <br>
            Eventos La Salle • Contacto: soporte@eventoslasalle.com
        </div>
    </div>
    
    @php
        }
    @endphp

    <!-- RENDERIZAR TITULAR -->
    @php
        renderTicket(
            'TITULAR', 
            $registration->name, 
            'Curso: ' . $registration->course, 
            $registration->event, 
            $registration->qr_token, 
            $registration->qr_image, 
            'REG-' . $registration->id
        );
    @endphp

    <!-- RENDERIZAR ACOMPAÑANTES -->
    @foreach($registration->guests as $guest)
        <div class="page-break"></div>
        @php
            renderTicket(
                'ACOMPAÑANTE', 
                $guest->name, 
                'Invitado de: ' . $registration->name, 
                $registration->event, 
                $guest->qr_token, 
                $guest->qr_image ?? null,
                'GST-' . $guest->id
            );
        @endphp
    @endforeach

</body>
</html>
