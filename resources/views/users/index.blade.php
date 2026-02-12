@extends('layouts.app')

@section('content')
<div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">User Management</h2>
        <span class="text-sm text-gray-500">{{ $users->count() }} users</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6">#</th>
                    <th class="py-3 px-6">Name</th>
                    <th class="py-3 px-6">Email</th>
                    <th class="py-3 px-6">Role</th>
                    <th class="py-3 px-6">Registered</th>
                    <th class="py-3 px-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm">
                @foreach($users as $index => $user)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6">{{ $index + 1 }}</td>
                    <td class="py-3 px-6 font-medium">
                        {{ $user->name }}
                        @if($user->id === auth()->id())
                        <span class="text-xs text-teal-600 ml-1">(You)</span>
                        @endif
                    </td>
                    <td class="py-3 px-6">{{ $user->email }}</td>
                    <td class="py-3 px-6">
                        @php
                        $roleColors = [
                        'admin' => 'bg-red-100 text-red-800',
                        'editor' => 'bg-blue-100 text-blue-800',
                        'viewer' => 'bg-gray-100 text-gray-800',
                        ];
                        @endphp
                        <span
                            class="{{ $roleColors[$user->role] ?? 'bg-gray-100' }} text-xs font-semibold px-2.5 py-1 rounded-full uppercase">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="py-3 px-6 text-gray-500">{{ $user->created_at->format('d M, Y') }}</td>
                    <td class="py-3 px-6 text-center">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.updateRole', $user) }}"
                            class="inline-flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="role"
                                class="text-xs border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-teal-500 focus:border-teal-500">
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor</option>
                                <option value="viewer" {{ $user->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                            </select>
                            <button type="submit"
                                class="bg-teal-600 hover:bg-teal-700 text-white text-xs px-3 py-1.5 rounded-lg transition font-medium">
                                Update
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection