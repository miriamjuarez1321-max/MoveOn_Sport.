<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prenda;
use App\Http\Requests\StorePrendaRequest;
use Illuminate\Support\Facades\Log;

class PrendaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Prenda::query();

        if ($search) {
            // Limpiar la búsqueda de palabras vacías
            $cleanSearch = str_ireplace([' de ', ' para ', ' la ', ' el ', ' los ', ' las ', ' un ', ' una ', ' y '], ' ', ' ' . $search . ' ');
            // Filtrar espacios vacíos y obtener un arreglo con los términos útiles
            $terms = array_filter(explode(' ', trim($cleanSearch)));

            $query->where(function ($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->where(function ($subQ) use ($term) {
                        $subQ->where('nombre', 'LIKE', "%{$term}%")
                             ->orWhere('descripcion', 'LIKE', "%{$term}%")
                             ->orWhere('categoria', 'LIKE', "%{$term}%")
                             ->orWhere('tipo', 'LIKE', "%{$term}%");
                    });
                }
            });
        }

        $prendas = $query->latest()->paginate(12);

        return view('collections.index', compact('prendas', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categoria = $request->query('categoria', 'hombre'); // Default to hombre if not provided
        return view('admin.products.form', compact('categoria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrendaRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('imagen')) {
                // Guarda la imagen en storage/app/public/prendas
                $path = $request->file('imagen')->store('prendas', 'public');
                $validated['imagen'] = $path;
            }

            $prenda = Prenda::create($validated);

            // Guardar stock por tallas
            if ($request->has('tallas_selected')) {
                foreach ($request->tallas_selected as $talla) {
                    $stock = $request->stocks[$talla] ?? 0;
                    $prenda->tallas()->create([
                        'talla' => $talla,
                        'stock' => $stock
                    ]);
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Producto publicado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al crear una prenda: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error inesperado al guardar la prenda.');
        }
    }

    public function show(Prenda $prenda)
    {
        $prenda->load(['comentarios.user', 'tallas']);
        
        $promedio = $prenda->comentarios->avg('calificacion') ?? 0;
        $totalComentarios = $prenda->comentarios->count();

        return view('products.show', compact('prenda', 'promedio', 'totalComentarios'));
    }
}
