@extends('layouts.admin')

@section('page_title', 'Quản lý Thành viên')

@section('admin_content')
<div class="space-y-6">
    {{-- Search & Controls --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <form action="{{ route('admin.members.index') }}" method="GET" class="flex items-center space-x-2 w-full md:w-96">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm tên hoặc email..." 
                       class="w-full bg-[#18181b] border border-zinc-800 text-[#fafafa] placeholder-zinc-500 rounded-lg py-2 pl-10 pr-4 text-sm focus:outline-none focus:ring-1 focus:ring-zinc-700">
            </div>
            <button type="submit" class="bg-white hover:bg-zinc-200 text-black px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                Tìm kiếm
            </button>
            @if($search)
                <a href="{{ route('admin.members.index') }}" class="text-zinc-400 hover:text-white text-sm px-2">
                    Xóa lọc
                </a>
            @endif
        </form>
        <div class="text-sm text-zinc-400">
            Tổng cộng: <span class="text-white font-semibold">{{ $users->total() }}</span> thành viên
        </div>
    </div>

    {{-- Members Table --}}
    <div class="type-card rounded-lg overflow-hidden border border-zinc-800 bg-[#18181b]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-800 bg-zinc-950 text-xs font-bold uppercase tracking-wider text-zinc-400">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Họ và tên</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Quyền</th>
                        <th class="py-4 px-6">Ngày tham gia</th>
                        <th class="py-4 px-6 text-right">Chi tiết</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800 text-sm text-zinc-300">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-900/50 transition-colors">
                            <td class="py-4 px-6 font-mono text-zinc-400">
                                {{ $user->display_id ?? '#' . substr($user->id, 0, 8) }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-white">
                                {{ $user->name }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $user->email }}
                            </td>
                            <td class="py-4 px-6">
                                @if($user->role === 'admin')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-950 text-red-400 border border-red-900/50">
                                        Quản trị viên
                                    </span>
                                @elseif($user->role === 'seller')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-950 text-blue-400 border border-blue-900/50">
                                        Người bán
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-zinc-800 text-zinc-300 border border-zinc-700/50">
                                        Khách hàng
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-zinc-400">
                                {{ $user->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.members.show', $user->id) }}" class="inline-flex items-center justify-center p-2 rounded-lg bg-zinc-800 hover:bg-zinc-700 text-white transition-all" title="Xem chi tiết">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-zinc-500">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-8 h-8 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span>Không tìm thấy thành viên nào.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-zinc-800 bg-zinc-950/50 flex justify-between items-center">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
