<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'email' => 'El campo :attribute debe ser un correo electrónico válido.',
    'string' => 'El campo :attribute debe ser texto.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'url' => 'El campo :attribute debe ser una URL válida.',
    'exists' => 'El valor seleccionado para :attribute no es válido.',
    'unique' => 'El valor de :attribute ya está en uso.',
    'min' => ['numeric' => 'El campo :attribute debe ser al menos :min.'],
    'max' => ['string' => 'El campo :attribute no debe superar :max caracteres.'],
    'gte' => ['numeric' => 'El campo :attribute debe ser mayor o igual que :value.'],
    'attributes' => [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
        'name' => 'nombre',
        'category_id' => 'categoría',
        'short_description' => 'descripción breve',
        'description' => 'descripción',
        'price' => 'precio',
        'compare_at_price' => 'precio anterior',
        'stock' => 'existencias',
        'size_label' => 'presentación',
        'image_url' => 'URL de imagen',
    ],
];
