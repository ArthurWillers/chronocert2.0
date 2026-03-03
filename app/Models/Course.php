<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'name',
        'total_hours',
        'user_id',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Course $course) {
            $course->categories()->each(fn (Category $category) => $category->delete());
        });
    }

    protected function casts(): array
    {
        return [
            'total_hours' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
