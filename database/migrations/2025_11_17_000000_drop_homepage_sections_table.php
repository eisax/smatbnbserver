<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('homepage_sections');
    }

    public function down(): void
    {
        if (!Schema::hasTable('homepage_sections')) {
            Schema::create('homepage_sections', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('section_type', [
                    'agents_list_section',
                    'articles_section',
                    'categories_section',
                    'faqs_section',
                    'featured_properties_section',
                    'featured_projects_section',
                    'most_liked_properties_section',
                    'most_viewed_properties_section',
                    'nearby_properties_section',
                    'projects_section',
                    'premium_properties_section',
                    'user_recommendations_section',
                    'properties_by_cities_section',
                ]);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order');
                $table->timestamps();
            });
        }
    }
};
