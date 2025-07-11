<?php
namespace Database\Seeders;

use App\Models\Lection;
use Illuminate\Database\Seeder;

class LectionSeeder extends Seeder
{
    public function run()
    {
        Lection::create([
            'subject_id' => 1,
            'topic_id' => 1,
            'user_id' => 1, // Ensure this matches an existing user
            'content' => '# Sample Lection\nThis is a **Markdown** content for the lection.',
        ]);
    }
}
