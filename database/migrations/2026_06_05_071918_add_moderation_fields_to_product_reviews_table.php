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
        Schema::table('product_reviews', function (Blueprint $table) {
            if (! Schema::hasColumn('product_reviews', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }

            if (! Schema::hasColumn('product_reviews', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('rejection_reason');
            }

            if (! Schema::hasColumn('product_reviews', 'moderated_by_user_id')) {
                $table->foreignId('moderated_by_user_id')
                    ->nullable()
                    ->after('moderated_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasIndex('product_reviews', ['status', 'moderated_at'])) {
                $table->index(['status', 'moderated_at']);
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            if (Schema::hasIndex('product_reviews', ['status', 'moderated_at'])) {
                $table->dropIndex(['status', 'moderated_at']);
            }

            if (Schema::hasColumn('product_reviews', 'moderated_by_user_id')) {
                $table->dropConstrainedForeignId('moderated_by_user_id');
            }

            foreach (['moderated_at', 'rejection_reason'] as $column) {
                if (Schema::hasColumn('product_reviews', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
