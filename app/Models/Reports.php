<?php

namespace App\Models;

use App\Concerns\Filterable;
use App\Concerns\HasBulkAction;
use App\Enum\ReportStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;
    use Filterable;
    use HasBulkAction;

    protected $fillable = ['user_id' ,'title','content', 'solved'];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];


    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function scopeStatus(Builder $builder, ?string $status = null) {
        if ($status) {
            $builder->whereSolved($status === ReportStatus::RESOLVED);
        }

        return $builder;
    }
}
