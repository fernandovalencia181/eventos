<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
// Librerías nativas de QR (vienen con Laravel Jetstream/Fortify)
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class PdfController extends Controller
{
    public function descargar(Ticket $ticket)
    {
        // 1. Seguridad
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        // 2. Generar QR (SVG)
        $contenido = json_encode(['id' => $ticket->id, 'sec' => $ticket->token_seguridad_qr]);
        
        $renderer = new ImageRenderer(
            new RendererStyle(200, 1),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrBase64 = base64_encode($writer->writeString($contenido));
        $qrImage = 'data:image/svg+xml;base64,' . $qrBase64;

        // 3. Generar PDF
        $pdf = Pdf::loadView('pdf.ticket', [
            'ticket' => $ticket,
            'qrCode' => $qrImage
        ]);

        return $pdf->download('entrada-' . $ticket->evento->nombre . '.pdf');
    }
}