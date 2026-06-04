<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $hasDuplicateUserReviews = DB::table('product_reviews')
            ->select('product_id', 'user_id')
            ->whereNotNull('user_id')
            ->groupBy('product_id', 'user_id')
            ->havingRaw('count(*) > 1')
            ->exists();

        Schema::table('product_reviews', function (Blueprint $table) use ($hasDuplicateUserReviews) {
            if (
                ! $hasDuplicateUserReviews
                && ! Schema::hasIndex('product_reviews', 'product_reviews_product_user_unique')
            ) {
                $table->unique(['product_id', 'user_id'], 'product_reviews_product_user_unique');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            if (Schema::hasIndex('product_reviews', 'product_reviews_product_user_unique')) {
                $table->dropUnique('product_reviews_product_user_unique');
            }
        });
    }
};
