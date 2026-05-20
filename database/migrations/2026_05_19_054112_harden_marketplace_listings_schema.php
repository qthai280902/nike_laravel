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
        DB::table('marketplace_listings')
            ->whereNull('status')
            ->orWhereNotIn('status', ['pending', 'active', 'rejected', 'sold'])
            ->update(['status' => 'pending']);

        DB::table('marketplace_listings')
            ->whereNull('condition')
            ->orWhereNotIn('condition', ['new_with_box', 'like_new', 'good', 'fair'])
            ->update(['condition' => 'good']);

        Schema::table('marketplace_listings', function (Blueprint $table) {
            if (! Schema::hasIndex('marketplace_listings', ['status'])) {
                $table->index('status');
            }

            if (! Schema::hasIndex('marketplace_listings', ['user_id'])) {
                $table->index('user_id');
            }

            if (! Schema::hasIndex('marketplace_listings', ['product_variant_id'])) {
                $table->index('product_variant_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketplace_listings', function (Blueprint $table) {
            //
        });
    }
};
