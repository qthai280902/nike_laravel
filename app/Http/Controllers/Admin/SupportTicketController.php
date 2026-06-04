<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the support tickets.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $query = SupportTicket::query();

        if ($status && in_array($status, ['open', 'in_progress', 'resolved', 'closed'])) {
            $query->where('status', $status);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        return view('admin.support.index', compact('tickets'));
    }

    /**
     * Display the specified support ticket details.
     */
    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['user', 'resolver']);

        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Update the specified support ticket.
     */
    public function update(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:open,in_progress,resolved,closed',
            'admin_note' => 'nullable|string',
        ], [
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        if (in_array($validated['status'], ['resolved', 'closed'], true)) {
            $validated['resolved_at'] = $ticket->resolved_at ?? now();
            $validated['resolved_by_user_id'] = $request->user()->id;
        } else {
            $validated['resolved_at'] = null;
            $validated['resolved_by_user_id'] = null;
        }

        $ticket->update($validated);

        return redirect()->route('admin.support.show', $ticket)
            ->with('success', 'Cập nhật trạng thái yêu cầu hỗ trợ thành công.');
    }
}
