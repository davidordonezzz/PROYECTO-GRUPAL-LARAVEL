<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador para gestionar productos en el panel de administración.
 * 
 * Incluye todas las operaciones CRUD, filtros, paginación,
 * SoftDelete, restauración y borrado definitivo.
 * También gestiona la subida de imágenes a Cloudinary.
 */
class ProductController extends Controller
{
    /**
     * Servicio para gestionar imágenes en Cloudinary.
     */
    protected CloudinaryService $cloudinaryService;

    /**
     * Constructor del controlador.
     * Inyecta el servicio de Cloudinary.
     */
    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Muestra el listado de productos con paginación y filtros.
     * 
     * Filtros disponibles:
     * - brand_id: Filtra por marca
     * - category_id: Filtra por categoría
     * - search: Búsqueda por nombre o SKU
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        // Query base con relaciones
        $query = Product::with(['brand', 'categories']);

        // Filtro por marca
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filtro por categoría
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Búsqueda por nombre o SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Ordenar por más recientes y paginar (10 por página)
        $products = $query->latest()->paginate(10)->withQueryString();

        // Obtener marcas y categorías para los filtros
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     *
     * @return View
     */
    public function create(): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('brands', 'categories'));
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     * Si se proporciona imagen, la sube a Cloudinary.
     *
     * @param StoreProductRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Procesar imagen si se ha subido
        if ($request->hasFile('image')) {
            $imageData = $this->cloudinaryService->uploadImage($request->file('image'));
            $data['image_url'] = $imageData['url'];
            $data['cloudinary_public_id'] = $imageData['public_id'];
        }

        // Establecer valor por defecto para 'active' si no viene en el request
        $data['active'] = $request->boolean('active', true);

        // Crear producto
        $product = Product::create($data);

        // Sincronizar categorías (relación n:m)
        if (isset($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Muestra los detalles de un producto específico.
     *
     * @param Product $product
     * @return View
     */
    public function show(Product $product): View
    {
        $product->load(['brand', 'categories']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Muestra el formulario para editar un producto existente.
     *
     * @param Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        $brands = Brand::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $product->load('categories');

        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Actualiza un producto existente en la base de datos.
     * Si se proporciona nueva imagen, elimina la anterior de Cloudinary.
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        // Procesar imagen si se ha subido una nueva
        if ($request->hasFile('image')) {
            $imageData = $this->cloudinaryService->updateImage(
                $request->file('image'),
                $product->cloudinary_public_id
            );
            $data['image_url'] = $imageData['url'];
            $data['cloudinary_public_id'] = $imageData['public_id'];
        }

        // Establecer valor para 'active'
        $data['active'] = $request->boolean('active', false);

        // Actualizar producto
        $product->update($data);

        // Sincronizar categorías (relación n:m)
        if (isset($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Elimina un producto (SoftDelete - borrado lógico).
     * El producto se marca como eliminado pero permanece en la BD.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete(); // SoftDelete

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente. Puedes restaurarlo desde la papelera.');
    }

    /**
     * Muestra la papelera con productos eliminados (SoftDeleted).
     *
     * @return View
     */
    public function trash(): View
    {
        $products = Product::onlyTrashed()
            ->with(['brand', 'categories'])
            ->latest('deleted_at')
            ->paginate(10);

        return view('admin.products.trash', compact('products'));
    }

    /**
     * Restaura un producto eliminado (SoftDeleted).
     *
     * @param int $id ID del producto a restaurar
     * @return RedirectResponse
     */
    public function restore(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', 'Producto restaurado correctamente.');
    }

    /**
     * Elimina un producto de forma permanente (borrado físico).
     * También elimina la imagen de Cloudinary si existe.
     *
     * @param int $id ID del producto a eliminar definitivamente
     * @return RedirectResponse
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        // Eliminar imagen de Cloudinary si existe
        if ($product->cloudinary_public_id) {
            $this->cloudinaryService->deleteImage($product->cloudinary_public_id);
        }

        // Eliminar relaciones con categorías
        $product->categories()->detach();

        // Eliminar definitivamente
        $product->forceDelete();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', 'Producto eliminado permanentemente.');
    }
}
