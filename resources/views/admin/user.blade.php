@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Kelola User</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Tambah --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Tambah User</h2>
        <form action="{{ route('admin.user.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <input type="text" name="name" placeholder="Nama Lengkap" required
                    class="border rounded px-3 py-2 text-sm">
                <input type="email" name="email" placeholder="Email" required
                    class="border rounded px-3 py-2 text-sm">
                <input type="password" name="password" placeholder="Password" required
                    class="border rounded px-3 py-2 text-sm">
                <select name="role" required class="border rounded px-3 py-2 text-sm">
                    <option value="">-- Pilih Role --</option>
                    <option value="peminjam">Peminjam</option>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-semibold">
                    Tambah User
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Daftar --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $u)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $u->name }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $u->email }}</td>
                    <td class="px-4 py-2 text-sm">
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $u->role === 'admin'    ? 'bg-red-100 text-red-700' : '' }}
                            {{ $u->role === 'petugas'  ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $u->role === 'peminjam' ? 'bg-green-100 text-green-700' : '' }}">
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-sm text-right space-x-2">
                        <a href="{{ route('admin.user.edit', $u->id) }}"
                            class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                        <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Hapus user ini?')"
                                class="text-red-600 hover:text-red-900 font-medium">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-400">Belum ada data user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection