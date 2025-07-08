<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image'
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function countLectureTopics()
    {
        return $this->topics()->where('type', 'lecture')->count();
    }

    public function countPracticeTopics()
    {
        return $this->topics()->where('type', 'practical')->count();
    }
}
