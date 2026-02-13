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
        Schema::table('ai_logs', function (Blueprint $table) {
            $table->string('type')->default('tool_invoked')->after('invocation_id');
            $table->string('tool')->nullable()->change();
            $table->json('arguments')->nullable()->change();
            $table->text('prompt')->nullable()->after('tool');
            $table->json('response')->nullable()->after('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_logs', function (Blueprint $table) {
            $table->dropColumn(['type', 'prompt', 'response']);
            $table->string('tool')->nullable(false)->change();
            $table->json('arguments')->nullable(false)->change();
        });
    }
};
