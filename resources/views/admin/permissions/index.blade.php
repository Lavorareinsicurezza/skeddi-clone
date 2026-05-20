@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.permissions') }}</h1>
        @can('create permissions')
        <a href="{{ route('admin.permissions.create') }}" class="px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm flex" title="{{ __('lang.create_permission') }}">
            <i class="text-gray-900 fa fa-plus"></i>
        </a>
        @endcan
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 bg-white">
            <thead class="text-xs text-white uppercase bg-blue-600 border-b">
                <tr>
                    <th scope="col" class="px-6 py-3 font-bold">{{ __('lang.name') }}</th>
                    <th scope="col" class="px-6 py-3 font-bold">{{ __('lang.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $permission)
                    <tr class="bg-white border-b border-gray-200">
                        <td class="px-6 py-4">{{ $permission->name }}</td>
                        <td class="px-6 py-4">
                            @can('view permissions')
                            <a href="{{ route('admin.permissions.show', $permission->id) }}" class="font-bold text-gray-900 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]" title="{{ __('lang.view') }}">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                            @endcan
                            @can('edit permissions')
                            <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="font-bold text-gray-900 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]" title="{{ __('lang.edit') }}">
                                <i class="fa fa-edit"></i>
                            </a>
                            @endcan
                            @can('delete permissions')
                            <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-bold text-red-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]" title="{{ __('lang.delete') }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="2" class="px-6 py-4 text-center text-gray-900">{{ __('lang.no_data_available') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $permissions->onEachSide(1)->links('pagination::tailwind') }}
    </div>
@endsection
