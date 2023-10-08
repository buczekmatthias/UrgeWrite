<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    public static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    protected $fillable = [
        'content',
        'isDone'
    ];

    protected $casts = [
        'isDone' => 'boolean'
    ];

    public function group()
    {
        return $this->belongsTo(TaskGroup::class);
    }
}
