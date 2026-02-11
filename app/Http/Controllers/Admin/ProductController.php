<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Listado paginado con filtros por marca, categoría y búsqueda
     */
    public function index(Request $request): View
    {
        $products = Product::with(['brand', 'categories'])
            ->search($request->input('search'))
            ->byBrand($request->input('brand_id'))
            ->byCategory($request->input('category_id'))
            ->latest()
            ->paginate(10);

        $brands     = Brand::where('active', true)->orderBy('name')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    /**
     * Formulario de creación
     */
    public function create(): View
    {
        $brands     = Brand::where('active', true)->orderBy('name')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('brands', 'categories'));
    }

    /**
     * Guardar nuevo producto
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $data['active'] = $request->boolean('active');

        $product = Product::create($data);

        // Asignar categorías (relación n:m)
        $product->categories()->sync($data['categories']);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto «{$product->name}» creado correctamente.");
    }

    /**
     * Ver detalle del producto
     */
    public function show(Product $product): View
    {
        $product->load(['brand', 'categories']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Product $product): View
    {
        $brands     = Brand::where('active', true)->orderBy('name')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();

        $product->load('categories');

        return view('admin.products.edit', compact('product', 'brands', 'categories'));
    }

    /**
     * Actualizar producto + reemplazar imagen en Cloudinary si se sube una nueva
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $data['active'] = $request->boolean('active');

        $product->update($data);

        // Sincronizar categorías
        $product->categories()->sync($data['categories']);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto «{$product->name}» actualizado correctamente.");
    }

    /**
     * Borrado lógico (SoftDelete) → va a la papelera
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete(); // SoftDelete

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto «{$product->name}» movido a la papelera.");
    }

    // ── Papelera (Trash) ────────────────────────────────

    /**
     * Listado de productos en papelera
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
     * Restaurar producto desde la papelera
     */
    public function restore(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', "Producto «{$product->name}» restaurado correctamente.");
    }

    /**
     * Borrado definitivo (físico) + eliminar imagen de Cloudinary
     */
    public function forceDelete(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);


        $product->forceDelete();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', "Producto eliminado definitivamente.");
    }
}
