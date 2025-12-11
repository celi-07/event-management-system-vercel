<?php

namespace App\Models;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'invitee_id',
        'status',
        'sent_at',
        'responded_at',
    ];

    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function invitee() {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
