<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'company_name',
        'street_address'
    ];
    public function products(){
        return  $this->hasMany('App\Models\Product');
    }
}
