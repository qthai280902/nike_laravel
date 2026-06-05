<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InventoryReturnService
{
    /**
     * Return stock for a cancelled order exactly once.
     */
    public function returnForCancelledOrder(Order $order, ?User $admin = null): void
    {
        DB::transaction(function () use ($order, $admin): void {
            /** @var Order|null $lockedOrder */
            $lockedOrder = Order::query()
                ->whereKey($order->getKey())
                ->lockForUpdate()
                ->first();

            if (! $lockedOrder) {
                return;
            }

            if ($lockedOrder->inventory_returned_at !== null) {
                return;
            }

            if ($lockedOrder->status !== 'cancelled') {
                return;
            }

            $returnedItemsCount = 0;
            $skippedItemsCount = 0;

            $lockedOrder->load('items');

            foreach ($lockedOrder->items as $item) {
                if ((int) $item->quantity <= 0 || ! $item->product_variant_id) {
                    $skippedItemsCount++;

                    continue;
                }

                /** @var ProductVariant|null $variant */
                $variant = ProductVariant::query()
                    ->whereKey($item->product_variant_id)
                    ->lockForUpdate()
                    ->first();

                if (! $variant) {
                    $skippedItemsCount++;

                    continue;
                }

                $variant->increment('stock', (int) $item->quantity);
                $returnedItemsCount++;
            }

            $note = 'Hoàn kho khi admin hủy đơn.';

            if ($skippedItemsCount > 0) {
                $note .= " Bỏ qua {$skippedItemsCount} item thiếu dữ liệu variant hoặc quantity hợp lệ.";
            }

            if ($returnedItemsCount === 0) {
                $note .= ' Không có item hợp lệ để cộng tồn kho.';
            }

            $lockedOrder->forceFill([
                'inventory_returned_at' => now(),
                'inventory_returned_by_user_id' => $admin?->id,
                'inventory_return_note' => $note,
            ])->save();
        });
    }
}
