<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            // Ensure score is integer
            $table->integer('score')->unsigned()->change();
            // Add correct, total, and answers if missing
            if (!Schema::hasColumn('test_results', 'correct')) {
                $table->integer('correct')->unsigned()->after('score');
            }
            if (!Schema::hasColumn('test_results', 'total')) {
                $table->integer('total')->unsigned()->after('correct');
            }
            if (!Schema::hasColumn('test_results', 'answers')) {
                $table->json('answers')->after('total');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_results', function (Blueprint $table) {
            $table->string('score')->change();
            if (Schema::hasColumn('test_results', 'correct')) {
                $table->dropColumn('correct');
            }
            if (Schema::hasColumn('test_results', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('test_results', 'answers')) {
                $table->dropColumn('answers');
            }
        });
    }
};
