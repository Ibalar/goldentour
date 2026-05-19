<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('pricing_title')->nullable()->after('faq_items');
            $table->text('pricing_subtitle')->nullable()->after('pricing_title');
            $table->json('pricing_plans')->nullable()->after('pricing_subtitle');
            $table->json('pricing_features')->nullable()->after('pricing_plans');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['pricing_title', 'pricing_subtitle', 'pricing_plans', 'pricing_features']);
        });
    }
};
