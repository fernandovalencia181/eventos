<?php

namespace App\Http\Controllers;

use App\Models\Evento; // <--- Importante: Importar el modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventoController extends Controller
{
    // Función para mostrar la página principal
    public function index()
    {
        // 1. Pedimos los eventos a la BD, filtrando solo los futuros
        $eventos = Evento::where('fecha', '>=', now())
                         ->orderBy('fecha', 'asc')
                         ->get(); 

        // 2. Retornamos la vista 'pages.welcome' y le pasamos los datos
        return view('pages.welcome', compact('eventos'));
    }
    public function dashboard()
    {
        // 1. Obtenemos todos los eventos de la base de datos
        // Usamos latest() para que salgan los nuevos primero y paginamos
        $eventos = Evento::latest()->paginate(10); 

        // 2. Retornamos la vista del admin pasando los datos
        return view('admin.panel', compact('eventos'));
    }
    // Muestra el formulario
    public function create()
    {
        return view('eventos.create');
    }
    // Guarda los datos en la BD
    public function store(Request $request)
    {
        // 1. Validar
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha' => 'required|date|after:today',
            'lugar' => 'required|string',
            'aforo_maximo' => 'required|integer|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen (máx 2MB)
        ]);

        // 2. Manejar la subida de la imagen
        if ($request->hasFile('imagen')) {
            // Guarda la imagen en la carpeta 'public/eventos' y nos da la ruta
            $path = $request->file('imagen')->store('eventos', 'public');
            $validated['imagen'] = $path;
        }

        // 3. Crear el evento
        Evento::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Evento creado con éxito.');
    }
    // --- FUNCIÓN 1: Mostrar formulario de edición ---
    public function edit(Evento $evento)
    {
        return view('eventos.edit', compact('evento'));
    }

    // --- FUNCIÓN 2: Guardar los cambios en la BD ---
    public function update(Request $request, Evento $evento)
    {
        // 1. Validamos igual que al crear
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'lugar' => 'required|string',
            'aforo_maximo' => 'required|integer|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 2. Manejar la subida de la nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior si existe
            if ($evento->imagen) {
                Storage::disk('public')->delete($evento->imagen);
            }
            
            // Guardar la nueva
            $path = $request->file('imagen')->store('eventos', 'public');
            $validated['imagen'] = $path;
        }

        // 3. Actualizamos el evento
        $evento->update($validated);

        // 4. Volvemos al dashboard con mensaje de éxito
        return redirect()->route('admin.dashboard')->with('success', 'Evento actualizado correctamente.');
    }

    // --- FUNCIÓN 3: Eliminar ---
    public function destroy(Evento $evento)
    {
        $evento->delete();
        return redirect()->route('admin.dashboard')->with('success', 'Evento eliminado.');
    }

    // --- FUNCIÓN 4: Ver Inscritos ---
    public function users(Request $request, Evento $evento)
    {
        // Query base
        $query = $evento->registrations()->with('guests', 'user');

        // Búsqueda por texto (Nombre, Email, Teléfono del User, o Nombre de invitado)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // El titular
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  // Sus invitados
                  ->orWhereHas('guests', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  })
                  // Su teléfono (si es usuario registrado)
                  ->orWhereHas('user', function($q3) use ($search) {
                      $q3->where('phone', 'like', "%$search%");
                  });
            });
        }

        // Filtro por Ciclo
        if ($request->has('course') && $request->course != '') {
            $query->where('course', $request->course);
        }

        $registrations = $query->latest()->paginate(20)->withQueryString();
        
        // Obtener lista de ciclos únicos para el dropdown
        $cursos = $evento->registrations()->select('course')->distinct()->pluck('course');

        return view('admin.eventos.users', compact('evento', 'registrations', 'cursos'));
    }
}
