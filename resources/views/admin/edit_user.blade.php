@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Edit User</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $user->name }}" required
                    class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $user->email }}" required
                    class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password <span class="text-gray-400 font-normal">(kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input type="password" name="password"
                    class="w-full border rounded px-3 py-2 text-sm">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required class="w-full border rounded px-3 py-2 text-sm">
                    <option value="peminjam" {{ $user->role === 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                    <option value="petugas"  {{ $user->role === 'petugas'  ? 'selected' : '' }}>Petugas</option>
                    <option value="admin"    {{ $user->role === 'admin'    ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex items-center gap-4">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded text-sm font-semibold">
                    Update User
                </button>
                <a href="{{ route('admin.user') }}" class="text-sm text-gray-500 hover:text-gray-800">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection