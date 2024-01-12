<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MessageReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['monitor_id', 'message_id', 'review', 'deleted_at'];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'message_id');
    }
}
