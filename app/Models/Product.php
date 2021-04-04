<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'product_image'
    ];
    public function company(){
        return $this->belongsTo('App\Models\Company');
    }
}
