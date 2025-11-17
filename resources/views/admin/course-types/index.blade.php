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
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.course_types_management') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">

            <a href="{{ route('admin.course-types.create') }}"
                class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 text-sm flex" title="{{ __('lang.create_course_type') }}">
                <i class="text-gray-500 fa fa-plus"></i>
            </a>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 bg-white">
            <thead class="text-xs text-gray-900 uppercase bg-white border-b">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        {{ __('lang.course_type') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('lang.years_of_validity') }}
                    </th>
                    <th scope="col" class="px-6 py-3">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($courseTypes->count() > 0)
                    @foreach ($courseTypes as $courseType)
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-6 py-4">
                                {{ $courseType->course_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $courseType->validity_year }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.course-types.show', $courseType->id) }}"
                                    class="font-medium text-gray-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.view') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.course-types.edit', $courseType->id) }}"
                                    class="font-medium text-gray-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.course-types.destroy', $courseType->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('lang.delete_course_type_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium text-red-500 p-2 hover:bg-red-50 border border-gray-200 rounded-[10px]"
                                        title="{{ __('lang.actions') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $courseTypes->onEachSide(1)->links('pagination::tailwind') }}
    </div>

@endsection
