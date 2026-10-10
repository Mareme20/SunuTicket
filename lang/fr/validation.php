<?php

return [
    'required' => 'Ce champ est obligatoire.',
    'string' => 'Ce champ doit être un texte.',
    'email' => 'Entrez une adresse email valide.',
    'integer' => 'Entrez un nombre entier.',
    'boolean' => 'Valeur invalide.',
    'date' => 'Entrez une date valide.',
    'after' => 'La date doit être dans le futur.',
    'confirmed' => 'La confirmation ne correspond pas.',
    'unique' => 'Ce :attribute est déjà utilisé.',
    'regex' => 'Format invalide. Exemple : 77 123 45 67.',
    'image' => 'Le fichier doit être une image.',
    'mimes' => 'L’image doit être au format JPG, PNG ou WebP.',
    'max' => [
        'string' => 'Maximum :max caractères.',
        'numeric' => 'La valeur maximale est :max.',
        'file' => 'Le fichier ne doit pas dépasser :max Ko.',
    ],
    'min' => [
        'string' => 'Minimum :min caractères.',
        'numeric' => 'La valeur minimale est :min.',
    ],
    'attributes' => [
        'phone' => 'numéro de téléphone',
        'password' => 'mot de passe',
    ],
];
