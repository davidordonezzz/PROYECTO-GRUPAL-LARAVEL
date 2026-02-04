<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request para validar la creación de un producto.
 * 
 * Valida todos los campos obligatorios y opcionales necesarios
 * para crear un nuevo producto en el sistema.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para esta petición.
     * La autorización real se maneja en el middleware 'admin'.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para crear un producto.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'description' => ['nullable', 'string', 'max:5000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:99999'],
            'brand_id' => ['required', 'exists:brands,id'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'active' => ['boolean'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'sku.required' => 'El código SKU es obligatorio.',
            'sku.unique' => 'Este código SKU ya existe en otro producto.',
            'sku.max' => 'El SKU no puede superar los 50 caracteres.',
            'description.max' => 'La descripción no puede superar los 5000 caracteres.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
            'price.min' => 'El precio no puede ser negativo.',
            'price.max' => 'El precio no puede superar 99999.99€.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'brand_id.required' => 'Debes seleccionar una marca.',
            'brand_id.exists' => 'La marca seleccionada no existe.',
            'categories.required' => 'Debes seleccionar al menos una categoría.',
            'categories.min' => 'Debes seleccionar al menos una categoría.',
            'categories.*.exists' => 'Una de las categorías seleccionadas no existe.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif o webp.',
            'image.max' => 'La imagen no puede superar los 2MB.',
        ];
    }

    /**
     * Nombres personalizados de los atributos.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'sku' => 'código SKU',
            'description' => 'descripción',
            'price' => 'precio',
            'stock' => 'stock',
            'brand_id' => 'marca',
            'categories' => 'categorías',
            'image' => 'imagen',
            'active' => 'activo',
        ];
    }
}
