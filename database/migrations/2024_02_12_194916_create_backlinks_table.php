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
        Schema::create('backlinks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->references('id')->on('sites');
            $table->text('link_url',500);
            $table->text('linked_url',500)->nullable()->default(null);
            $table->smallInteger('domain_rank')->nullable()->unsigned();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->dateTime('last_checked_at')->nullable()->default(null);
            $table->text("contact_email",100);
            $table->text("contact_name",100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlinks');
    }
};
