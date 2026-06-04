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
        if (DB::getDriverName() === 'sqlite') {
            $this->rebuildSqliteTable();

            return;
        }

        Schema::table('marketplace_listings', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
        });

        Schema::table('marketplace_listings', function (Blueprint $table) {
            $table->uuid('product_variant_id')->nullable()->change();

            if (! Schema::hasColumn('marketplace_listings', 'product_name')) {
                $table->string('product_name')->nullable()->after('product_variant_id');
            }

            if (! Schema::hasColumn('marketplace_listings', 'brand')) {
                $table->string('brand')->nullable()->after('product_name');
            }

            if (! Schema::hasColumn('marketplace_listings', 'size')) {
                $table->string('size')->nullable()->after('brand');
            }

            if (! Schema::hasColumn('marketplace_listings', 'color')) {
                $table->string('color')->nullable()->after('size');
            }

            if (! Schema::hasColumn('marketplace_listings', 'image_url')) {
                $table->string('image_url')->nullable()->after('color');
            }
        });

        Schema::table('marketplace_listings', function (Blueprint $table) {
            $table->foreign('product_variant_id')
                ->references('id')
                ->on('product_variants')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non-destructive rollback: these columns may contain seller-created listings.
    }

    private function rebuildSqliteTable(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::rename('marketplace_listings', 'marketplace_listings_legacy');

        Schema::create('marketplace_listings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('image_url')->nullable();
            $table->decimal('asking_price', 15, 2);
            $table->string('condition');
            $table->text('seller_description');
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });

        DB::statement(<<<'SQL'
            insert into marketplace_listings (
                id,
                user_id,
                product_variant_id,
                asking_price,
                condition,
                seller_description,
                status,
                deleted_at,
                created_at,
                updated_at
            )
            select
                id,
                user_id,
                product_variant_id,
                asking_price,
                condition,
                seller_description,
                status,
                deleted_at,
                created_at,
                updated_at
            from marketplace_listings_legacy
        SQL);

        Schema::drop('marketplace_listings_legacy');
        Schema::enableForeignKeyConstraints();
    }
};
