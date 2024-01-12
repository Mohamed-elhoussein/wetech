<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderSkill extends Model
{
    use HasFactory;

    protected $fillable = ['skill_id', 'user_id'];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class)->whereRole('provider');
    }
}
