@extends('layouts.admin')

@section('page_title', 'Chi tiết Yêu cầu Hỗ trợ')

@section('admin_content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.support.index') }}" 
        class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase rounded-lg border border-zinc-800 bg-zinc-900 text-zinc-400 hover:text-white transition-all">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Quay lại danh sách
    </a>
</div>

@if (session('success'))
    <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 mb-6 rounded-lg font-nike-body text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Ticket Detail Column (left 2 cols) -->
    <div class="lg:col-span-2 space-y-6">
        <div class="type-card p-8 rounded-xl space-y-6">
            <div>
                <span class="text-xs text-zinc-500 uppercase tracking-widest font-mono">Yêu cầu #{{ $ticket->id }}</span>
                <h3 class="text-xl font-bold text-white mt-1 uppercase tracking-tight">{{ $ticket->subject }}</h3>
                <p class="text-xs text-zinc-400 mt-1">Được gửi lúc: {{ $ticket->created_at->format('H:i d/m/Y') }}</p>
            </div>

            <hr class="border-zinc-800">

            <div class="space-y-4">
                <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Nội dung chi tiết</h4>
                <div class="bg-zinc-950/60 border border-zinc-900 p-6 rounded-lg text-zinc-300 whitespace-pre-wrap leading-relaxed text-sm">
                    {{ $ticket->message }}
                </div>
            </div>
        </div>
    </div>

    <!-- Sender & Actions Column (right 1 col) -->
    <div class="space-y-6">
        <!-- Sender info card -->
        <div class="type-card p-6 rounded-xl space-y-6">
            <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Thông tin người gửi</h4>
            
            <div class="space-y-4 text-sm">
                <div>
                    <label class="block text-[10px] text-zinc-500 uppercase tracking-widest">Họ và tên</label>
                    <p class="font-bold text-white mt-0.5">{{ $ticket->name }}</p>
                </div>
                
                <div>
                    <label class="block text-[10px] text-zinc-500 uppercase tracking-widest">Địa chỉ Email</label>
                    <p class="text-zinc-300 mt-0.5">{{ $ticket->email }}</p>
                </div>

                <div>
                    <label class="block text-[10px] text-zinc-500 uppercase tracking-widest">Loại tài khoản</label>
                    <div class="mt-1">
                        @if ($ticket->user)
                            <a href="{{ route('admin.members.show', $ticket->user) }}" 
                                class="inline-flex items-center text-xs text-blue-400 hover:underline">
                                Thành viên ({{ $ticket->user->name }})
                                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        @else
                            <span class="text-zinc-500">Khách vãng lai</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Ticket status edit card -->
        <div class="type-card p-6 rounded-xl space-y-6">
            <h4 class="text-xs font-bold text-zinc-400 uppercase tracking-wider">Xử lý yêu cầu</h4>
            
            <form action="{{ route('admin.support.update', $ticket) }}" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="status" class="block text-[10px] text-zinc-500 uppercase tracking-widest mb-2">Trạng thái xử lý</label>
                    <select name="status" id="status" 
                        class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded text-zinc-300 text-sm focus:outline-none focus:border-zinc-700">
                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Mới gửi (Open)</option>
                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>Đang xử lý (In Progress)</option>
                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Đã xử lý (Resolved)</option>
                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Đã đóng (Closed)</option>
                    </select>
                </div>

                <div>
                    <label for="admin_note" class="block text-[10px] text-zinc-500 uppercase tracking-widest mb-2">Ghi chú xử lý</label>
                    <textarea name="admin_note" id="admin_note" rows="5" 
                        class="w-full px-3 py-2 bg-zinc-950 border border-zinc-800 rounded text-zinc-300 text-sm focus:outline-none focus:border-zinc-700"
                        placeholder="Nhập ghi chú phản hồi, giải quyết yêu cầu...">{{ $ticket->admin_note }}</textarea>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full bg-white hover:bg-zinc-200 text-black font-bold uppercase py-2.5 rounded text-xs tracking-wider transition-all">
                        Cập nhật trạng thái
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
