<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name'         => ['required', 'string', 'max:255'],
            'sku'          => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'description'  => ['nullable', 'string', 'max:5000'],
            'price'        => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock'        => ['required', 'integer', 'min:0'],
            'brand_id'     => ['required', 'exists:brands,id'],
            'categories'   => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'tags'         => ['nullable', 'array'],
            'tags.*'       => ['exists:tags,id'],
            'image'        => [$this->isMethod('POST') ? 'nullable' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'active'       => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'El nombre del producto es obligatorio.',
            'name.max'             => 'El nombre no puede superar los 255 caracteres.',
            'sku.required'         => 'El SKU es obligatorio.',
            'sku.unique'           => 'Este SKU ya está en uso.',
            'price.required'       => 'El precio es obligatorio.',
            'price.min'            => 'El precio debe ser mayor que 0.',
            'stock.required'       => 'El stock es obligatorio.',
            'stock.min'            => 'El stock no puede ser negativo.',
            'brand_id.required'    => 'Debes seleccionar una marca.',
            'brand_id.exists'      => 'La marca seleccionada no existe.',
            'categories.required'  => 'Debes seleccionar al menos una categoría.',
            'categories.min'       => 'Debes seleccionar al menos una categoría.',
            'image.image'          => 'El archivo debe ser una imagen.',
            'image.mimes'          => 'La imagen debe ser JPG, PNG o WebP.',
            'image.max'            => 'La imagen no puede superar los 2 MB.',
        ];
    }
}
