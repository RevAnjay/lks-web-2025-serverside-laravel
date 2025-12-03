<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentApplySocieties extends Model
{
    protected $table = 'installment_apply_societies';

    protected $fillable = [
        'society_id',
        'installment_id',
        'available_month_id',
        'notes',
        'date'
    ];

    public function car()
    {
        return $this->belongsTo(InstallmentCars::class, 'installment_id');
    }

    public function society()
    {
        return $this->belongsTo(User::class, 'society_id');
    }

    public function availableMonth()
    {
        return $this->belongsTo(AvailableMonth::class, 'available_month_id');
    }

    public function status()
    {
        return $this->belongsTo(InstallmentApplyStatus::class, 'status_id');
    }
}
