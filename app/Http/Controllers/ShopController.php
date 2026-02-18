<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ShopController extends Controller
{
    // Listado de productos con filtros
    public function index(Request $request): View
    {
        $products = Product::with(['brand', 'categories', 'tags'])
            ->where('active', true)
            ->where('stock', '>', 0)
            ->search($request->input('search'))
            ->byBrand($request->input('brand_id'))
            ->byCategory($request->input('category_id'))
            ->latest()
            ->paginate(12);

        $brands     = Brand::where('active', true)->orderBy('name')->get();
        $categories = Category::where('active', true)->orderBy('name')->get();

        return view('shop.index', compact('products', 'brands', 'categories'));
    }

    // Página de contacto
    public function contact(): View
    {
        return view('shop.contact');
    }

    // Detalle de un producto
    public function show(Product $product): View
    {
        abort_if(! $product->active, 404);

        $product->load(['brand', 'categories', 'tags']);

        // Productos relacionados (misma marca o categoría)
        $related = Product::where('active', true)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->where(function ($q) use ($product) {
                $q->where('brand_id', $product->brand_id)
                    ->orWhereHas('categories', function ($cq) use ($product) {
                        $cq->whereIn('categories.id', $product->categories->pluck('id'));
                    });
            })
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'related'));
    }

    // Compra directa
    public function buy(Request $request, Product $product)
    {
        // Validar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para comprar.');
        }

        abort_if(!$product->active || $product->stock <= 0, 404);

        // Validar cantidad
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
        ]);

        $quantity = $request->input('quantity', 1);

        if ($quantity > $product->stock) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        $amount = $product->price * $quantity;

        // Guardar en sesión para que PayPalController lo use
        session([
            'purchase' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'amount' => $amount,
                'user_id' => Auth::id(),
            ],
        ]);

        // Redirigir a crear el pago de PayPal
        return redirect()->route('paypal.create');
    }
}
