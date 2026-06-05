<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InventoryReturnService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly InventoryReturnService $inventoryReturnService
    ) {}

    /**
     * Display a listing of the orders.
     */
    public function index(Request $request): View
    {
        $query = Order::with('user')->latest();

        // Search by order ID, shipping name, shipping email or user details
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('shipping_name', 'like', "%{$search}%")
                    ->orWhere('shipping_email', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.variant.product', 'inventoryReturnedBy']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,paid,shipped,delivered,cancelled',
        ]);

        $currentStatus = $order->status;
        $newStatus = $request->input('status');

        if ($currentStatus === $newStatus) {
            return back()->with('info', 'Trạng thái không thay đổi.');
        }

        // Rules of transitions
        // - pending -> paid, shipped, cancelled
        // - paid -> shipped, cancelled
        // - shipped -> delivered
        // - delivered is locked
        // - cancelled is locked

        if ($currentStatus === 'delivered' || $currentStatus === 'cancelled') {
            return back()->with('error', 'Không thể đổi trạng thái đơn đã hoàn tất/đã hủy.');
        }

        $allowed = false;
        if ($currentStatus === 'pending') {
            $allowed = in_array($newStatus, ['paid', 'shipped', 'cancelled'], true);
        } elseif ($currentStatus === 'paid') {
            $allowed = in_array($newStatus, ['shipped', 'cancelled'], true);
        } elseif ($currentStatus === 'shipped') {
            $allowed = ($newStatus === 'delivered');
        }

        if (! $allowed) {
            return back()->with('error', 'Chuyển đổi trạng thái từ "'.$this->getStatusLabel($currentStatus).'" sang "'.$this->getStatusLabel($newStatus).'" không hợp lệ.');
        }

        $wasInventoryReturned = $order->inventory_returned_at !== null;

        $order->update(['status' => $newStatus]);

        if ($newStatus === 'cancelled') {
            $this->inventoryReturnService->returnForCancelledOrder($order->fresh(), $request->user());

            if ($wasInventoryReturned) {
                return back()->with('info', 'Hủy đơn thành công nhưng đơn đã được hoàn kho trước đó.');
            }

            return back()->with('success', 'Hủy đơn thành công và đã hoàn kho.');
        }

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng thành công.');
    }

    /**
     * Get VietNamese label for order status.
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Chờ xử lý',
            'paid' => 'Đã thanh toán',
            'shipped' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
            default => $status,
        };
    }
}
