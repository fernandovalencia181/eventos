<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: sans-serif; color: #1e293b; text-align: center; }
        .box { border: 2px dashed #cbd5e1; padding: 40px; margin: 20px auto; max-width: 600px; border-radius: 10px; }
        .title { color: #4f46e5; font-size: 24px; font-weight: bold; margin-bottom: 10px; }
        .meta { color: #64748b; margin-bottom: 20px; }
        .qr-box { margin: 30px 0; }
        .footer { font-size: 10px; color: #94a3b8; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="box">
        <div class="title">{{ $ticket->evento->nombre }}</div>
        
        <div class="meta">
            Fecha: {{ \Carbon\Carbon::parse($ticket->evento->fecha)->format('d/m/Y h:i A') }} <br>
            Asistente: <strong>{{ $ticket->nombre_asistente ?? $ticket->usuario->name }}</strong>
        </div>

        <div class="qr-box">
            <img src="{{ $qrCode }}" width="220" height="220">
        </div>

        <p style="font-family: monospace; color: #94a3b8;">ID: {{ $ticket->id }}</p>

        <div class="footer">Entrada personal e intransferible.</div>
    </div>
</body>
</html>