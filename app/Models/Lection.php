<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lection extends Model
{
    protected $fillable = ['subject_id', 'topic_id', 'user_id', 'content'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
