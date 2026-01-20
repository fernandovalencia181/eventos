<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Entrada - {{ $registration->event->nombre }}</title>
    <style>
        body { font-family: sans-serif; }
        .page-break { page-break-after: always; }
        .ticket { border: 2px solid #000; padding: 20px; margin-bottom: 20px; text-align: center; }
        .header { background-color: #f0f0f0; padding: 10px; margin-bottom: 20px; font-weight: bold; font-size: 1.2em; }
        .qr-code { margin: 20px 0; }
        .footer { font-size: 0.8em; color: #666; margin-top: 20px; }
        .course { font-weight: bold; margin-bottom: 10px; }
        .guest-info { font-style: italic; margin-bottom: 15px; font-size: 0.9em; }
    </style>
</head>
<body>
    
    <!-- PÁGINA 1: TITULAR -->
    <div class="ticket">
        <div class="header">{{ $registration->event->nombre }}</div>
        
        <h2>{{ $registration->name }}</h2>
        <div class="course">Curso: {{ $registration->course }}</div>
        <div class="qr-code">
            <img src="{{ $registration->qr_image ?? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.$registration->qr_token }}" alt="QR Titular" width="200">
        </div>
        
        <p>Fecha: {{ $registration->event->fecha }}</p>
        <p>Lugar: {{ $registration->event->lugar }}</p>
        
        <div class="footer">ID Entrada: #{{ $registration->id }} - TITULAR</div>
    </div>

    @foreach($registration->guests as $guest)
        <div class="page-break"></div>
        
        <!-- PÁGINA EXTRA: ACOMPAÑANTE -->
        <div class="ticket">
            <div class="header">{{ $registration->event->nombre }}</div>
            
            <h2>{{ $guest->name }}</h2>
            <div class="guest-info">Invitado por: {{ $registration->name }}</div>
            
            <div class="qr-code">
                <img src="{{ $guest->qr_image ?? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='.$guest->qr_token }}" alt="QR Invitado" width="200">
            </div>
            
            <p>Fecha: {{ $registration->event->fecha }}</p>
            <p>Lugar: {{ $registration->event->lugar }}</p>
            
            <div class="footer">ID Entrada: #{{ $registration->id }}-G{{ $guest->id }} - ACOMPAÑANTE</div>
        </div>
    @endforeach

</body>
</html>
