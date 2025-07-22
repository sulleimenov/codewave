<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('commands', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
            $table->unsignedBigInteger('subject_id')->nullable()->after('id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('commands', function (Blueprint $table) {
            $table->dropColumn('subject_id');
            $table->unsignedBigInteger('topic_id')->nullable()->after('id');
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }
};
