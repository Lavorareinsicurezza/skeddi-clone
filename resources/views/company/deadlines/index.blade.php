@extends('layouts.app')

@section('content')

    <!-- Header Section -->
    <div class="mb-4 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">

        <!-- Heading -->
        <h1 class="text-lg md:text-xl font-semibold text-gray-900 whitespace-nowrap">
            {{ __('lang.deadlines') }}
        </h1>

        <!-- Filter Form -->
        {{-- <form id="filterForm" method="GET" action="{{ route('admin.deadlines') }}"
            class="border border-gray-200 rounded-md overflow-hidden shadow-sm bg-white w-full lg:w-auto">

            <div class="flex flex-col sm:flex-row sm:items-center">

                <!-- Left Section -->
                <div class="p-1.5 sm:border-r border-b sm:border-b-0 border-gray-200 bg-blue-100">
                    <div class="cursor-pointer font-semibold text-[#0C3183] rounded px-3 py-2 text-xs">
                        {{ __('lang.deadlines') }}
                    </div>
                </div>

                <!-- Status -->
                <div class="p-1.5 sm:border-r border-b sm:border-b-0 border-gray-200">
                    <div id="statusFilter"
                        class="cursor-pointer {{ request('scheduled') != 'false' ? 'bg-blue-50 text-[#0C3183]' : 'text-gray-500' }} rounded px-3 py-2 font-semibold text-xs">
                        {{ __('lang.to_be_scheduled') }}
                        <input type="hidden" name="scheduled" id="scheduledInput" value="{{ request('scheduled') }}">
                    </div>
                </div>

                <!-- From Date -->
                <div
                    class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    <label for="fromDate" class="text-gray-600 font-medium whitespace-nowrap">{{ __('lang.from') }}:</label>
                    <input type="date" name="from_date" id="fromDate" value="{{ request('from_date') }}"
                        class="bg-transparent border-0 border-b border-gray-300 px-1 py-0.5 text-gray-600 font-medium focus:outline-none w-full sm:w-auto text-xs" />
                </div>

                <!-- To Date -->
                <div
                    class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    <label for="toDate" class="text-gray-600 font-medium whitespace-nowrap">{{ __('lang.to') }}:</label>
                    <input type="date" name="to_date" id="toDate" value="{{ request('to_date') }}"
                        class="bg-transparent border-0 border-b border-gray-300 px-1 py-0.5 text-gray-600 font-medium focus:outline-none w-full sm:w-auto text-xs" />
                </div>

                <!-- Reset Filter -->
                <div class="px-3 py-2 flex justify-center sm:justify-start">
                    <button type="button" id="resetFilter"
                        class="flex items-center text-red-600 cursor-pointer hover:text-red-700 text-xs font-medium space-x-1">
                        <i class="fas fa-rotate-left w-3 h-3"></i>
                        <span>{{ __('lang.reset') }}</span>
                    </button>
                </div>

            </div>
        </form> --}}
    </div>



    <!-- Table Section -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 bg-white">
            <thead class="text-xs text-gray-900 uppercase bg-white border-b">
                <tr>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap">
                        {{ __('lang.company_name') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap">
                        {{ __('lang.course_name') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap">
                        {{ __('lang.employee_name') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap">
                        {{ __('lang.expiry_date') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trainingPlans as $plan)
                    <tr class="bg-white border-b border-gray-200">
                        <th scope="row" class="px-3 md:px-6 py-4 font-medium text-gray-500 whitespace-nowrap">
                            {{ $plan->company?->name }}
                        </th>
                        <td class="px-3 md:px-6 py-4">
                            {{ $plan->companyCourseType?->name }}
                        </td>
                        <td class="px-3 md:px-6 py-4">
                            {{ $plan->worker?->surname }}
                        </td>
                        <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                            @php
                                $expDate = $plan->expiration_date;
                                $now = \Carbon\Carbon::now();
                                $daysUntilExpiry = $now->diffInDays($expDate, false);

                                if ($daysUntilExpiry < 0) {
                                    $colorClass = 'text-red-600'; // Expired
                                } elseif ($daysUntilExpiry <= 30) {
                                    $colorClass = 'text-orange-500'; // Expiring soon
                                } else {
                                    $colorClass = 'text-green-600'; // Valid
                                }
                            @endphp
                            <span class="{{ $colorClass }}">{{ \Carbon\Carbon::parse($plan->expiration_date)->format('d F Y') }}</span>
                        </td>
                        <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a
                                    class="font-medium text-[#0C3183] p-2 ">
                                   {{ __('lang.renew') }}
                                </a>
                                {{-- <a href="#"
                                    class="font-medium text-red-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]">
                                    <i class="fa fa-trash"></i>
                                </a> --}}
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="5" class="px-3 md:px-6 py-4 text-center text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            const statusFilter = document.getElementById('statusFilter');
            const fromDate = document.getElementById('fromDate');
            const toDate = document.getElementById('toDate');
            const resetFilter = document.getElementById('resetFilter');
            const scheduledInput = document.getElementById('scheduledInput');

            function submitForm() {
                filterForm.submit();
            }

            if (statusFilter) {
                statusFilter.addEventListener('click', function () {
                    if (scheduledInput.value == 'true') {
                        scheduledInput.value = 'false';
                    } else {
                        scheduledInput.value = 'true';
                    }
                    submitForm();
                });
            }

            if (fromDate) fromDate.addEventListener('change', submitForm);
            if (toDate) toDate.addEventListener('change', submitForm);

            if (resetFilter) {
                resetFilter.addEventListener('click', function () {
                    scheduledInput.value = 'false';
                    fromDate.value = '';
                    toDate.value = '';
                    window.location.href = "{{ route('admin.deadlines') }}";
                });
            }
        });

    </script>
@endsection
