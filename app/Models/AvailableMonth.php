<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailableMonth extends Model
{
    protected $table = 'available_month';

    public function Car()
    {
        return $this->belongsTo(InstallmentCars::class, 'installment_id');
    }
}
