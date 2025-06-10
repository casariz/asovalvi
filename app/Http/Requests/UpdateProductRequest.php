<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user() && in_array(auth()->user()->role, ['admin', 'manager', 'employee']);
    }

    public function rules(): array
    {
        $productId = $this->route('product')->id ?? $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $productId,
            'category_id' => 'required|exists:categories,id',
            'weight' => 'nullable|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'care_instructions' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'string',
            'is_active' => 'boolean',
            'status' => 'in:available,out_of_stock,discontinued',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'sku.required' => 'El SKU es obligatorio.',
            'sku.unique' => 'Este SKU ya existe.',
            'category_id.required' => 'La categoría es obligatoria.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'status.in' => 'El estado debe ser: disponible, agotado o descontinuado.',
        ];
    }
}