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
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.visit_type') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <a href="{{ route('admin.company-visit-types.create') }}"
                class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm flex"
                title="{{ __('lang.create') . ' ' . __('lang.worker') }}">
                <i class="text-gray-500 fa fa-plus"></i>
            </a>
        </div>
    </div>

    <!-- Course Types Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.name') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.specific_name') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.years_of_validity') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.expiry_date') }}
                    </th>
                    <th scope="col" class="px-6 py-4 text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($visitTypes as $visitType)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitType->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitType->specific_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitType->expiry_date ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $visitType->notes ?? '-' }}
                        </td>
                         <td class="px-6 py-4">
                                <a href="{{ route('admin.company-visit-types.show', $visitType->id) }}"
                                    class="font-medium text-gray-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.view') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.company-visit-types.edit', $visitType->id) }}"
                                    class="font-medium text-gray-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.company-visit-types.destroy', $visitType->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('lang.delete_course_type_confirm') }}');">
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
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($visitTypes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $visitTypes->links() }}
            </div>
        @endif
    </div>

@endsection
