@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Roles</h1>
        @can('create roles')
        <a href="{{ route('admin.roles.create') }}" class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 text-sm flex" title="Create Role">
            <i class="text-gray-500 fa fa-plus"></i>
        </a>
        @endcan
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 bg-white">
            <thead class="text-xs text-gray-900 uppercase bg-white border-b">
                <tr>
                    <th scope="col" class="px-6 py-3">{{ __('lang.name') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('lang.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <tr class="bg-white border-b border-gray-200">
                        <td class="px-6 py-4">{{ $role->name }}</td>
                        <td class="px-6 py-4">
                            @can('view roles')
                            <a href="{{ route('admin.roles.show', $role->id) }}" class="font-medium text-gray-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]" title="View">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            @endcan
                            @can('edit roles')
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="font-medium text-gray-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]" title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endcan
                            @can('delete roles')
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="2" class="px-6 py-4 text-center text-gray-500">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $roles->onEachSide(1)->links('pagination::tailwind') }}
    </div>
@endsection
