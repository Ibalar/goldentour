<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Блок "Почему выбирают эту услугу"
            $table->string('why_choose_title')->nullable()->after('features');
            $table->text('why_choose_subtitle')->nullable()->after('why_choose_title');
            $table->json('why_choose_items')->nullable()->after('why_choose_subtitle');

            // Блок "Что входит"
            $table->string('offer_title')->nullable()->after('why_choose_items');
            $table->text('offer_subtitle')->nullable()->after('offer_title');
            $table->json('offer_list')->nullable()->after('offer_subtitle');
            $table->json('offer_items')->nullable()->after('offer_list');

            // Блок "Процесс работы"
            $table->string('process_title')->nullable()->after('offer_items');
            $table->text('process_subtitle')->nullable()->after('process_title');
            $table->json('process_items')->nullable()->after('process_subtitle');
            $table->string('process_image')->nullable()->after('process_items');

            // Блок FAQ "Что еще важно знать"
            $table->string('faq_title')->nullable()->after('process_image');
            $table->json('faq_items')->nullable()->after('faq_title');

            // Отображение в меню хидера
            $table->boolean('show_in_menu')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'why_choose_title',
                'why_choose_subtitle',
                'why_choose_items',
                'offer_title',
                'offer_subtitle',
                'offer_list',
                'offer_items',
                'process_title',
                'process_subtitle',
                'process_items',
                'process_image',
                'faq_title',
                'faq_items',
                'show_in_menu',
            ]);
        });
    }
};
