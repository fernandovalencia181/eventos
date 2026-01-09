<?php

namespace App\Http\Controllers;

use App\Models\Evento; // <--- Importante: Importar el modelo
use Illuminate\Http\Request;

class EventoController extends Controller
{
    // Función para mostrar la página principal
    public function index()
    {
        // 1. Pedimos los eventos a la BD (select * from eventos)
        $eventos = Evento::all(); 

        // 2. Retornamos la vista 'welcome' y le pasamos los datos
        return view('welcome', compact('eventos'));
    }
    // Muestra el formulario
    public function create()
    {
        return view('eventos.create');
    }
    // Guarda los datos en la BD
    public function store(Request $request)
    {
        // 1. Validar que no nos manden basura
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'lugar' => 'required|string',
            'aforo_maximo' => 'required|integer|min:1',
        ]);

        // 2. Crear el evento
        Evento::create($validated);

        // 3. Redirigir al inicio con un mensaje de éxito
        return redirect()->route('home')->with('success', '¡El evento se ha creado correctamente!');
    }
}