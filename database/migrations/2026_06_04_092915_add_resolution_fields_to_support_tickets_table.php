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
        Schema::table('support_tickets', function (Blueprint $table) {
            if (! Schema::hasColumn('support_tickets', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('admin_note');
            }

            if (! Schema::hasColumn('support_tickets', 'resolved_by_user_id')) {
                $table->foreignId('resolved_by_user_id')
                    ->nullable()
                    ->after('resolved_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            if (Schema::hasColumn('support_tickets', 'resolved_by_user_id')) {
                $table->dropConstrainedForeignId('resolved_by_user_id');
            }

            if (Schema::hasColumn('support_tickets', 'resolved_at')) {
                $table->dropColumn('resolved_at');
            }
        });
    }
};
