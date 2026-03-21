<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrendaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio_compra' => 'required|numeric|min:0.01',
            'precio_venta' => 'required|numeric|min:0.01|gt:precio_compra',
            'stock' => ['required', 'integer', 'min:2', function ($attribute, $value, $fail) {
                $stocks = $this->input('stocks', []);
                $tallas_selected = $this->input('tallas_selected', []);
                
                $sum = 0;
                foreach ($tallas_selected as $talla) {
                    $sum += (int) ($stocks[$talla] ?? 0);
                }

                if ($sum > (int) $value) {
                    $fail('La suma del stock por tallas no puede ser mayor al stock actual del producto.');
                }
            }],
            'talla' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'imagen' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,jpg,gif|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categoria' => 'required|in:hombre,mujer,accesorios',
            'tipo' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'precio_venta.min' => 'El precio debe ser mayor a 0.',
            'precio_venta.gt' => 'El precio de venta debe ser mayor al precio de compra.',
            'precio_compra.min' => 'El precio de compra debe ser mayor a 0.',
            'stock.min' => 'El stock debe ser mayor a 1.',
            'imagen.required' => 'La imagen es obligatoria para nuevos productos.',
        ];
    }
}
