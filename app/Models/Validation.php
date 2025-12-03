<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $table = 'validations';

    protected $fillable = [
        'society_id',
        'job',
        'job_description',
        'income',
        'reason_accepted'
    ];

    public function Society()
    {
        return $this->belongsTo(Society::class);
    }

    public function Validator()
    {
        return $this->belongsTo(Validator::class);
    }
}
