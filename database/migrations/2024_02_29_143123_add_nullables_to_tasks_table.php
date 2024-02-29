<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->json('task_data')->nullable()->change();
            $table->json('execution_data')->nullable()->change();
            $table->boolean('completed')->nullable()->change();
            $table->dateTime('completed_at')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn([
                'task_data', 'execution_data', 'completed', 'completed_at'
            ]);
            $table->json('task_data');
            $table->json('execution_data');
            $table->boolean('completed');
            $table->dateTime('completed_at');
        });
    }
};
