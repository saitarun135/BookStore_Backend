<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phoneNumber',
        'pincode',
        'locality',
        'city',
        'address',
        'landmark',
        'type'
    ];
    // public function orders(){
    //     return $this->hasMany('App\Models\Orders','customer_id');
    // }
}
