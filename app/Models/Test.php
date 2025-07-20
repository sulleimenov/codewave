<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = ['topic_id', 'id', 'title', 'user_id'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

//    public function results(): HasMany
//    {
//        return $this->hasMany(TestResult::class);
//    }

    public function results($userId = null): HasMany
    {
        $query = $this->hasMany(TestResult::class);
        if ($userId) {
            $query->where('user_id', $userId);
        }
        return $query;
    }
}
