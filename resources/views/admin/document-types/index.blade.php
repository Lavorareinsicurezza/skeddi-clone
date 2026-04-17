@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.document_types_management') }}</h1>
        @can('create document-types')
        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <a href="{{ route('admin.document-types.create') }}"
                class="px-5 py-3 font-bold text-gray-900 hover:text-blue-700 hover:bg-blue-50 text-sm flex" title="{{ __('lang.create_document_type') }}">
                <i class="text-gray-900 fa fa-plus"></i>
            </a>
        </div>
        @endcan
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 bg-white">
            <thead class="text-xs text-white uppercase bg-blue-600 border-b">
                <tr>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.document_type') }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.years_of_validity') }}
                    </th>
                    <th scope="col" class="px-6 py-3 font-bold">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($documentTypes->count() > 0)
                    @foreach ($documentTypes as $documentType)
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-6 py-4">
                                {{ $documentType->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $documentType->validity_year }}
                            </td>
                            <td class="px-6 py-4">
                                @can('view document-types')
                                <a href="{{ route('admin.document-types.show', $documentType->id) }}"
                                    class="font-bold text-gray-900 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.view') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                @endcan
                                @can('edit document-types')
                                <a href="{{ route('admin.document-types.edit', $documentType->id) }}"
                                    class="font-bold text-gray-900 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete document-types')
                                <form action="{{ route('admin.document-types.destroy', $documentType->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('lang.delete_document_type_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-bold text-red-500 p-2 hover:bg-red-50 border border-gray-200 rounded-[10px]"
                                        title="{{ __('lang.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="3" class="px-6 py-4 text-center text-gray-900">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $documentTypes->onEachSide(1)->links('pagination::tailwind') }}
    </div>

@endsection
