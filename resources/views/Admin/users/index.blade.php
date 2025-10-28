@extends('admin.layouts.app')

@section('title', __('Manajemen Pengguna'))
@section('header', __('Pengguna'))
@section('subheader', __('Kelola akun pengguna dan atur peran aksesnya.'))

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-md bg-emerald-50 border border-emerald-100 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-500/10 dark:border-emerald-500/30 dark:text-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-rose-50 border border-rose-100 px-4 py-3 text-sm text-rose-700 dark:bg-rose-500/10 dark:border-rose-500/30 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" placeholder="{{ __('Cari nama atau email...') }}" value="{{ $search }}"
                        class="w-64 rounded-md border-gray-300 pl-10 pr-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1010.5 18a7.5 7.5 0 006.15-3.35z" />
                        </svg>
                    </span>
                </div>
                <select name="role"
                    class="rounded-md border-gray-300 py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-900 dark:border-slate-700 dark:text-gray-100">
                    <option value="">{{ __('Semua Peran') }}</option>
                    <option value="admin" @selected($role === 'admin')>{{ __('Admin') }}</option>
                    <option value="user" @selected($role === 'user')>{{ __('Anggota') }}</option>
                </select>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                    {{ __('Filter') }}
                </button>
                @if ($search || $role)
                    <a href="{{ route('admin.users.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200">{{ __('Reset') }}</a>
                @endif
            </form>

            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Tambah Pengguna') }}
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm dark:bg-slate-900 dark:border-slate-800 dark:shadow-none">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-800">
                <thead class="bg-gray-50 dark:bg-slate-900/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Nama') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Email') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Peran') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                            {{ __('Dibuat') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32 dark:text-gray-300">
                            {{ __('Aksi') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-slate-900 dark:divide-slate-800">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                            @class([
                                                'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-200' => $user->role === 'admin',
                                                'bg-slate-100 text-slate-700 dark:bg-slate-500/20 dark:text-slate-200' => $user->role !== 'admin',
                                            ])">
                                    {{ $user->role === 'admin' ? __('Admin') : __('Anggota') }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">
                                {{ optional($user->created_at)->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="text-indigo-600 hover:text-indigo-500 font-medium dark:text-indigo-300 dark:hover:text-indigo-200">{{ __('Edit') }}</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Hapus pengguna ini?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-500 font-medium dark:text-rose-300 dark:hover:text-rose-200"
                                            @disabled(auth()->id() === $user->id)>{{ __('Hapus') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-300">
                                {{ __('Belum ada pengguna yang terdaftar.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dark:text-gray-300">
            {{ $users->links() }}
        </div>
    </div>
@endsection