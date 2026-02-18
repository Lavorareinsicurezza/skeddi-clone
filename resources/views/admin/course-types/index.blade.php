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

        <div class="flex items-center gap-2">
            @can('edit course-types')
            <button id="saveOrderBtn"
                class="hidden px-4 py-2 bg-[#0C3183] text-white text-sm font-semibold rounded-lg hover:bg-[#0A2869] flex items-center gap-2">
                <i class="fa fa-save"></i>
                {{ __('lang.save_order') }}
            </button>
            @endcan

            @can('create course-types')
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <a href="{{ route('admin.course-types.create') }}"
                    class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 text-sm flex" title="{{ __('lang.create_course_type') }}">
                    <i class="text-gray-500 fa fa-plus"></i>
                </a>
            </div>
            @endcan
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 bg-white">
            <thead class="text-xs text-gray-900 uppercase bg-white border-b">
                <tr>
                    <th scope="col" class="px-4 py-3 w-24">
                        {{ __('lang.order') }}
                    </th>
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
                        <tr class="bg-white border-b border-gray-200" data-id="{{ $courseType->id }}">
                            <td class="px-4 py-4">
                                @can('edit course-types')
                                <input type="number" min="1"
                                    class="sort-order-input w-16 text-center border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-[#0C3183]"
                                    value="{{ $courseType->sort_order }}"
                                    data-id="{{ $courseType->id }}"
                                    data-original="{{ $courseType->sort_order }}">
                                @else
                                    {{ $courseType->sort_order }}
                                @endcan
                            </td>
                            <td class="px-6 py-4">
                                {{ $courseType->course_name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $courseType->validity_year }}
                            </td>
                            <td class="px-6 py-4">
                                @can('view course-types')
                                <a href="{{ route('admin.course-types.show', $courseType->id) }}"
                                    class="font-medium text-gray-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.view') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                @endcan
                                @can('edit course-types')
                                <a href="{{ route('admin.course-types.edit', $courseType->id) }}"
                                    class="font-medium text-gray-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="{{ __('lang.edit') }}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete course-types')
                                <form action="{{ route('admin.course-types.destroy', $courseType->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('lang.delete_course_type_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium text-red-500 p-2 hover:bg-red-50 border border-gray-200 rounded-[10px]"
                                        title="{{ __('lang.actions') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saveBtn = document.getElementById('saveOrderBtn');
        const inputs = document.querySelectorAll('.sort-order-input');

        if (!saveBtn || !inputs.length) return;

        // Show Save button when any input changes
        inputs.forEach(function (input) {
            input.addEventListener('input', function () {
                saveBtn.classList.remove('hidden');
            });
        });

        saveBtn.addEventListener('click', function () {
            const order = [];
            inputs.forEach(function (input) {
                order.push({
                    id: parseInt(input.dataset.id),
                    sort_order: parseInt(input.value) || 1
                });
            });

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';

            fetch('{{ route('admin.course-types.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ order: order })
            })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) {
                    // Update original values and hide button
                    inputs.forEach(function (input) {
                        input.dataset.original = input.value;
                    });
                    saveBtn.classList.add('hidden');
                    saveBtn.innerHTML = '<i class="fa fa-save"></i> {{ __('lang.save_order') }}';
                    saveBtn.disabled = false;

                    // Reload to reflect sorted order
                    window.location.reload();
                }
            })
            .catch(function () {
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fa fa-save"></i> {{ __('lang.save_order') }}';
                alert('Error saving order. Please try again.');
            });
        });
    });
</script>
@endsection
