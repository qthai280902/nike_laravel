@extends('layouts.admin')

@section('page_title', 'Danh sách Yêu cầu Hỗ trợ')

@section('admin_content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex space-x-2">
        <a href="{{ route('admin.support.index') }}" 
            class="px-4 py-2 text-xs font-bold uppercase rounded-lg border transition-all {{ !request('status') ? 'bg-white text-black border-white' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white' }}">
            Tất cả
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'open']) }}" 
            class="px-4 py-2 text-xs font-bold uppercase rounded-lg border transition-all {{ request('status') === 'open' ? 'bg-yellow-500 text-black border-yellow-500' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white' }}">
            Mới gửi
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'in_progress']) }}" 
            class="px-4 py-2 text-xs font-bold uppercase rounded-lg border transition-all {{ request('status') === 'in_progress' ? 'bg-blue-500 text-white border-blue-500' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white' }}">
            Đang xử lý
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'resolved']) }}" 
            class="px-4 py-2 text-xs font-bold uppercase rounded-lg border transition-all {{ request('status') === 'resolved' ? 'bg-green-500 text-white border-green-500' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white' }}">
            Đã xử lý
        </a>
        <a href="{{ route('admin.support.index', ['status' => 'closed']) }}" 
            class="px-4 py-2 text-xs font-bold uppercase rounded-lg border transition-all {{ request('status') === 'closed' ? 'bg-zinc-700 text-zinc-300 border-zinc-700' : 'bg-zinc-900 text-zinc-400 border-zinc-800 hover:text-white' }}">
            Đã đóng
        </a>
    </div>
</div>

<div class="type-card rounded-xl overflow-hidden mb-8">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-900/50 text-zinc-500 uppercase text-[10px] tracking-widest border-b border-zinc-800">
                <tr>
                    <th class="px-6 py-4 font-medium">Thời gian gửi</th>
                    <th class="px-6 py-4 font-medium">Người gửi</th>
                    <th class="px-6 py-4 font-medium">Email</th>
                    <th class="px-6 py-4 font-medium">Tiêu đề</th>
                    <th class="px-6 py-4 font-medium">Trạng thái</th>
                    <th class="px-6 py-4 font-medium text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800">
                @forelse ($tickets as $ticket)
                    <tr class="hover:bg-zinc-900/30 transition-colors">
                        <td class="px-6 py-4 text-zinc-400 text-xs">
                            {{ $ticket->created_at->format('H:i d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-white">
                            {{ $ticket->name }}
                            @if ($ticket->user_id)
                                <span class="ml-1 px-1.5 py-0.5 text-[9px] font-bold bg-zinc-800 text-zinc-300 rounded uppercase">Thành viên</span>
                            @else
                                <span class="ml-1 px-1.5 py-0.5 text-[9px] font-bold bg-zinc-900 text-zinc-500 rounded uppercase">Khách</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-zinc-400 text-xs">
                            {{ $ticket->email }}
                        </td>
                        <td class="px-6 py-4 text-zinc-300">
                            {{ Str::limit($ticket->subject, 40) }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($ticket->status === 'open')
                                <span class="px-2.5 py-1 rounded-full bg-yellow-500/10 text-yellow-500 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $ticket->status_label }}
                                </span>
                            @elseif ($ticket->status === 'in_progress')
                                <span class="px-2.5 py-1 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $ticket->status_label }}
                                </span>
                            @elseif ($ticket->status === 'resolved')
                                <span class="px-2.5 py-1 rounded-full bg-green-500/10 text-green-500 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $ticket->status_label }}
                                </span>
                            @elseif ($ticket->status === 'closed')
                                <span class="px-2.5 py-1 rounded-full bg-zinc-800 text-zinc-400 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $ticket->status_label }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.support.show', $ticket) }}" 
                                class="inline-flex items-center justify-center p-2 bg-zinc-900 border border-zinc-800 hover:border-zinc-700 hover:bg-zinc-800 text-zinc-300 hover:text-white rounded-lg transition-all"
                                title="Xem chi tiết">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-zinc-500">
                            Không tìm thấy yêu cầu hỗ trợ nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if ($tickets->hasPages())
    <div class="mt-4">
        {{ $tickets->links() }}
    </div>
@endif
@endsection
