<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'email',
        'whatsapp',
        'country',
        'product_interest',
        'quantity',
        'message',
        'attachment',
        'ip_address',
    ];
}
