<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveSession extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_name',
        'status',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
