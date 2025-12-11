<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'host_id',
        'location',
        'description',
        'image',
        'status',
    ];

    public function host() {
        return $this->belongsTo(User::class, 'host_id');
    }
}
