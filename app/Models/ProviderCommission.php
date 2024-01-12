<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderCommission extends Model
{
    use HasFactory;
    protected $fillable = ['commission', 'provider_id', 'percentage', 'is_online'];
}
