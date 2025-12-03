<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Society extends Model
{
    use HasApiTokens;

    protected $table = 'societies';

    protected $fillable = [
        'name',
        'born_date',
        'id_card_number',
        'gender',
        'address',
        'regional'
    ];

    public function Regional()
    {
        return $this->belongsTo(Regional::class);
    }

    public function Validation()
    {
        return $this->hasMany(Validation::class);
    }

    public function Applications()
    {
        return $this->hasMany(InstallmentApplySocieties::class);
    }
}
