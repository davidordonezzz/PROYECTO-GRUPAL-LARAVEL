<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * Tienda pública — listado de productos con filtros
     */
    public function index(Request $request): View
    {
        $products = Product::with(['brand', 'categories'])
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

    /**
     * Detalle de un producto
     */
    public function show(Product $product): View
    {
        abort_if(! $product->active, 404);

        $product->load(['brand', 'categories']);

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

    /**
     * Compra directa — redirige a la pasarela de pago
     */
    public function buy(Request $request, Product $product)
    {
        abort_if(! $product->active || $product->stock <= 0, 404);

        $quantity = $request->input('quantity', 1);

        if ($quantity > $product->stock) {
            return back()->with('error', 'No hay suficiente stock disponible.');
        }

        $amount = $product->price * $quantity;

        // Guardar en sesión para que PaymentController lo use
        session([
            'purchase' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'amount' => $amount,
            ],
        ]);

        // Redirigir a crear el pago
        return redirect()->route('payment.create')
            ->withInput(['amount' => $amount]);
    }
}
