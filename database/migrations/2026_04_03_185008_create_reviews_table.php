<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->string('author_phone')->nullable();
            $table->string('author_email')->nullable();
            $table->tinyInteger('rating')->unsigned()->default(5);
            $table->text('text');
            $table->boolean('is_published')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('portfolio_id')->nullable()->constrained('portfolios')->nullOnDelete();
            $table->text('admin_reply')->nullable();
            $table->timestamps();

            $table->index('is_published');
            $table->index('rating');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
