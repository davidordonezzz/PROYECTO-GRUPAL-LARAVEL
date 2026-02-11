<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\TelegramService;

class ProductObserver
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function created(Product $product)
    {
        $message = "🆕 <b>Nuevo Producto Creado</b>\n\n";
        $message .= "📦 <b>Nombre:</b> {$product->name}\n";
        $message .= "🏷️ <b>SKU:</b> {$product->sku}\n";
        $message .= "💰 <b>Precio:</b> \${$product->price}\n";
        $message .= "📊 <b>Stock:</b> {$product->stock}\n";
        $message .= "📅 <b>Fecha:</b> " . $product->created_at->format('d/m/Y H:i');

        $this->telegram->notifyAdmin($message);
    }

    public function updated(Product $product)
    {
        $message = "🚀 <b>Producto Actualizado</b>\n\n";
        $message .= "📦 <b>Nombre:</b> {$product->name}\n";
        $message .= "🏷️ <b>SKU:</b> {$product->sku}\n";
        $message .= "💰 <b>Precio:</b> \${$product->price}\n";
        $message .= "📊 <b>Stock:</b> {$product->stock}\n";
        $message .= "📅 <b>Fecha:</b> " . now()->format('d/m/Y H:i');

        $this->telegram->notifyAdmin($message);
    }

    public function deleted(Product $product)
    {
        $message = "🗑️ <b>Producto Eliminado</b>\n\n";
        $message .= "📦 <b>Nombre:</b> {$product->name}\n";
        $message .= "🏷️ <b>SKU:</b> {$product->sku}\n";
        $message .= "💰 <b>Precio:</b> \${$product->price}\n";
        $message .= "📅 <b>Fecha:</b> " . now()->format('d/m/Y H:i');

        $this->telegram->notifyAdmin($message);
    }
}
