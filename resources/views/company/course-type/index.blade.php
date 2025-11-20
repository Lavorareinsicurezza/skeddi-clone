@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flash-message-box"
            role="alert">
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.company_course_types') }}</h1>

        <div class="flex items-center gap-3">
            <!-- Action Buttons -->
            <a href="{{ route('admin.company-course-types.export') }}"
                class="px-5 py-3 font-semibold text-[#0C3183] hover:bg-[#EBF1FF] text-sm border border-gray-300 rounded-lg flex items-center gap-2"
                title="{{ __('lang.export_data') }}">
                <i class="fa fa-download"></i>
                {{ __('lang.export_data') }}
            </a>
        </div>
    </div>

    <!-- Course Types Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.course_type') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.years_of_validity') }}
                    </th>
                    <th scope="col"
                        class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($companyCourseTypes as $companyCourseType)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $companyCourseType->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $companyCourseType->validity_years ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <div class="flex items-center justify-center gap-3">
                                <!-- View Button -->
                                <a href="{{ route('admin.company-course-types.show', $companyCourseType->id) }}"
                                    class="text-gray-500 hover:text-[#0C3183]" title="{{ __('lang.view') }}">
                                    <i class="fa fa-eye text-lg"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.company-course-types.destroy', $companyCourseType->id) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('{{ __('lang.delete_course_type_confirm') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600"
                                        title="{{ __('lang.delete') }}">
                                        <i class="fa fa-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($companyCourseTypes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $companyCourseTypes->links() }}
            </div>
        @endif
    </div>

    <!-- Floating Add Button -->
    <a href="{{ route('admin.company-course-types.create') }}"
        class="fixed bottom-8 right-8 w-14 h-14 bg-[#0C3183] hover:bg-[#0A2869] text-white rounded-full flex items-center justify-center shadow-lg transition-all hover:shadow-xl"
        title="{{ __('lang.create_course_type') }}">
        <i class="fa fa-plus text-xl"></i>
    </a>

@endsection
