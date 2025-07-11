<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->foreignId('leader_id')->constrained('users')->onDelete('cascade');
            $table->json('member_ids');
            $table->string('link')->default('standart_bars');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commands');
    }
};
