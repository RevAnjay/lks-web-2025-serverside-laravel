<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentCars extends Model
{
    protected $table = 'installment';

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function month()
    {
        return $this->hasMany(AvailableMonth::class,'installment_id');
    }
}
