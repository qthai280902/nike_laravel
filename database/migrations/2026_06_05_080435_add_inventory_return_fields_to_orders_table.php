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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'inventory_returned_at')) {
                $table->timestamp('inventory_returned_at')->nullable()->after('payment_method');
            }

            if (! Schema::hasColumn('orders', 'inventory_returned_by_user_id')) {
                $table->foreignId('inventory_returned_by_user_id')
                    ->nullable()
                    ->after('inventory_returned_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('orders', 'inventory_return_note')) {
                $table->text('inventory_return_note')->nullable()->after('inventory_returned_by_user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'inventory_returned_by_user_id')) {
                $table->dropConstrainedForeignId('inventory_returned_by_user_id');
            }

            foreach (['inventory_return_note', 'inventory_returned_at'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
