<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    // Listado paginado con filtros
    public function index(Request $request): View
    {
        $products = Product::with(['brand', 'categories', 'tags'])
            ->search($request->input('search'))
            ->byBrand($request->input('brand_id'))
            ->byCategory($request->input('category_id'))
            ->latest()
            ->paginate(10);

        $brands     = Brand::where('active', true)->orderBy('name')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'brands', 'categories'));
    }

    // Formulario de creación
    public function create(): View
    {
        $brands     = Brand::where('active', true)->orderBy('name')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();
        $tags       = Tag::where('active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('brands', 'categories', 'tags'));
    }

    // Guardar nuevo producto
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $data['active'] = $request->boolean('active');

        // Procesar imagen si se sube
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product = Product::create($data);

        // Asignar categorías
        $product->categories()->sync($data['categories']);

        // Asignar tags
        if (!empty($data['tags'])) {
            $product->tags()->sync($data['tags']);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto «{$product->name}» creado correctamente.");
    }

    // Ver detalle del producto
    public function show(Product $product): View
    {
        $product->load(['brand', 'categories', 'tags']);

        return view('admin.products.show', compact('product'));
    }

    // Formulario de edición
    public function edit(Product $product): View
    {
        $brands     = Brand::where('active', true)->orderBy('name')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();
        $tags       = Tag::where('active', true)->orderBy('name')->get();

        $product->load(['categories', 'tags']);

        return view('admin.products.edit', compact('product', 'brands', 'categories', 'tags'));
    }

    // Actualizar producto
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['name']);

        $data['active'] = $request->boolean('active');

        // Procesar imagen si se sube una nueva
        if ($request->hasFile('image')) {
            // Borrar imagen anterior si existe
            if ($product->image_url) {
                $oldPath = str_replace('/storage/', '', $product->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product->update($data);

        // Sincronizar categorías
        $product->categories()->sync($data['categories']);

        // Sincronizar tags
        $product->tags()->sync($data['tags'] ?? []);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto «{$product->name}» actualizado correctamente.");
    }

    // Borrado lógico (SoftDelete)
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete(); // SoftDelete

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Producto «{$product->name}» movido a la papelera.");
    }

    // Papelera

    // Listado de productos en papelera
    public function trash(): View
    {
        $products = Product::onlyTrashed()
            ->with(['brand', 'categories'])
            ->latest('deleted_at')
            ->paginate(10);

        return view('admin.products.trash', compact('products'));
    }

    // Restaurar producto
    public function restore(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', "Producto «{$product->name}» restaurado correctamente.");
    }

    // Borrado definitivo + eliminar imagen
    public function forceDelete(int $id): RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        // Borrar imagen del servidor si existe
        if ($product->image_url) {
            $path = str_replace('/storage/', '', $product->image_url);
            Storage::disk('public')->delete($path);
        }

        $product->forceDelete();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', "Producto eliminado definitivamente.");
    }
}
