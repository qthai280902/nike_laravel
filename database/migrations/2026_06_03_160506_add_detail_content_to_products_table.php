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
        $hasProductStory = Schema::hasColumn('products', 'product_story');
        $hasHighlights = Schema::hasColumn('products', 'highlights');
        $hasCareInstructions = Schema::hasColumn('products', 'care_instructions');

        Schema::table('products', function (Blueprint $table) use ($hasProductStory, $hasHighlights, $hasCareInstructions) {
            if (! $hasProductStory) {
                $table->longText('product_story')->nullable()->after('description');
            }

            if (! $hasHighlights) {
                $table->json('highlights')->nullable()->after('product_story');
            }

            if (! $hasCareInstructions) {
                $table->text('care_instructions')->nullable()->after('highlights');
            }

            $table->index(['status', 'featured_position'], 'products_status_featured_position_index');
            $table->index(['category_id', 'status'], 'products_category_status_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_status_featured_position_index');
            $table->dropIndex('products_category_status_index');
            $table->dropColumn(['product_story', 'highlights', 'care_instructions']);
        });
    }
};
