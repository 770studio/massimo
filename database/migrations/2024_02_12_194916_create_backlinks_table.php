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
            $table->string('link_anchor',255)->default('');
            $table->integer('status_code')->after('link_url')->nullable();
            $table->text('status_redirect_to',500)->nullable()->default(null)->after('status_code');
            $table->boolean('status_link_present')->after('status_redirect_to')->nullable();
            $table->string('status_link_rel',15)->after('status_link_present')->nullable();
            $table->boolean('status_page_indexed')->after('status_link_rel')->nullable();
            $table->text('linked_url',500)->nullable()->default(null);
            $table->smallInteger('domain_rank')->nullable()->unsigned();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->text("contact_email",100)->nullable();
            $table->text("contact_name",100)->nullable();;
            $table->dateTime('last_checked_at')->nullable()->default(null);
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
