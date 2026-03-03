<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarDetail extends Model
{
    protected $table = 'car_details';

    protected $fillable = [
           'carName',
        'make',
        'model',
        'body',
        'price',
        'year',
        'fuelType',
        'transmission',
        'color',
        'kilometers',
        'km',
        'owner',
        'mfg',
        'insurance',
        'pollution',
        'registration',
        'features',
        'coverImage',
        'galleryImages',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'year' => 'integer',
        'features' => 'array',
        'galleryImages' => 'array',
    ];
}
