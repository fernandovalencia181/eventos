<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Entrada - EventosU</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #033473 0%, #3074b8 100%);
            padding: 24px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #033473;
        }
        .message {
            font-size: 16px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 24px;
        }
        .event-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .event-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            border-bottom: 2px solid #3074b8;
            padding-bottom: 8px;
            display: inline-block;
        }
        .detail-row {
            display: flex;
            margin-bottom: 8px;
            font-size: 15px;
        }
        .detail-label {
            font-weight: 600;
            width: 80px;
            color: #64748b;
        }
        .detail-value {
            color: #334155;
            flex: 1;
        }
        .footer {
            background-color: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
        }
        .action-note {
            background-color: #eff6ff;
            border-left: 4px solid #3074b8;
            padding: 12px;
            font-size: 14px;
            color: #1e40af;
            margin-bottom: 24px;
        }
        @media only screen and (max-width: 600px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div style="padding: 24px;">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>EventosU</h1>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="greeting">¡Hola, {{ $registration->name }}!</div>
                
                <p class="message">
                    Tu registro para el evento ha sido confirmado exitosamente. A continuación encontrarás los detalles:
                </p>

                <div class="event-card">
                    <div class="event-title">{{ $registration->event->nombre }}</div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Fecha:</span>
                        <span class="detail-value text-capitalize">{{ \Carbon\Carbon::parse($registration->event->fecha)->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Hora:</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($registration->event->hora)->format('H:i') }} hs</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Lugar:</span>
                        <span class="detail-value">{{ $registration->event->ubicacion ?? 'Ubicación por confirmar' }}</span>
                    </div>

                    @if($registration->guests->count() > 0)
                    <div class="detail-row" style="margin-top: 12px; border-top: 1px dashed #cbd5e1; padding-top: 8px;">
                        <span class="detail-label">Entradas:</span>
                        <span class="detail-value">
                            1 General + {{ $registration->guests->count() }} Acompañante(s)
                        </span>
                    </div>
                    @endif
                </div>

                <div class="action-note">
                    <strong>Importante:</strong> Hemos adjuntado tu entrada en formato PDF a este correo. Por favor, descárgala y presentala (impresa o en tu móvil) al ingresar al evento.
                </div>

                <p class="message" style="margin-bottom: 0;">
                    Si tienes alguna duda, puedes contactarnos respondiendo a este correo o visitando nuestra plataforma.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>&copy; {{ date('Y') }} EventosU - La Salle Mollerussa. Todos los derechos reservados.</p>
                <p>Has recibido este correo porque te registraste en nuestra plataforma.</p>
            </div>
        </div>
    </div>
</body>
</html>
