<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    //

    protected $table = 'contact_us';

    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'whatsapp_number',
        'service_package',
        'location',
    ];
}
