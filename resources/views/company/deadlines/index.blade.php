@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="mb-4 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">

        <!-- Heading -->
        <h1 class="text-lg md:text-xl font-semibold text-gray-900 whitespace-nowrap">
            {{ __('lang.deadlines') }}
        </h1>

        <form id="filterForm" method="GET" action="{{ route('admin.deadlines') }}"
            class="border border-gray-200 rounded-md overflow-hidden shadow-sm bg-white w-full lg:w-auto">
            <div class="flex flex-col sm:flex-row sm:items-center">

                <!-- Operating Location -->
                <div class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    <label for="operatingLocation" class="text-gray-600 font-medium whitespace-nowrap">{{ __('lang.operating_location') }}:</label>
                    <select name="operating_location_id" id="operatingLocation"
                        class="bg-transparent border-0 border-b border-gray-300 px-1 py-0.5 text-gray-600 font-medium focus:outline-none w-full sm:w-auto text-xs">
                        <option value="">{{ __('lang.all') }}</option>
                        @foreach($operatingLocations as $location)
                            <option value="{{ $location->id }}" {{ (string)($selectedOperatingLocationId ?? '') === (string)$location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Deadline Type -->
                <div class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    <select name="deadline_type" id="deadline-type-filter"
                        class="bg-transparent border-0 border-b border-gray-300 px-1 py-0.5 text-gray-600 font-medium focus:outline-none w-full sm:w-auto text-xs">
                        <option value="all">{{ __('lang.all') }}</option>
                        <option value="training_plan" {{ request('deadline_type') == 'training_plan' ? 'selected': '' }}>{{ __('lang.training_plan') }}</option>
                        <option value="visits" {{ request('deadline_type') == 'visits' ? 'selected': '' }}>{{ __('lang.visit_type') }}</option>
                        <option value="documents" {{ request('deadline_type') == 'documents' ? 'selected': '' }}>{{ __('lang.documents') }}</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    <input type="text" name="search" id="search"
                        class="bg-transparent border-0 border-b border-gray-300 px-1 py-0.5 text-gray-600 font-medium focus:outline-none w-full sm:w-auto text-xs"
                        placeholder="{{ __('lang.search') }}" value="{{ request('search') }}">
                </div>

                <!-- Export -->
                <div class="px-3 py-2 flex items-center sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    <a href="{{ route('admin.deadlines.export', ['operating_location_id' => $selectedOperatingLocationId]) }}"
                       class="font-semibold text-gray-600 hover:text-[#0C3183] flex items-center gap-2">
                        <i class="fa fa-download"></i>
                        <span>{{ __('lang.export') }}</span>
                    </a>
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
        </form>
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
                        {{ __('lang.name') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap">
                        {{ __('lang.deadline_type') }}
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
                @forelse ($records as $plan)
                    <tr class="bg-white border-b">

                        <td class="px-3 md:px-6 py-4">
                            {{ $currentCompany->name }}
                        </td>

                        <td class="px-3 md:px-6 py-4">
                            @if($plan->notes)
                                <div class="relative inline-block">
                                    <span class="border-b border-dashed border-gray-400 cursor-help"
                                          data-notes="{{ json_encode([$plan->notes]) }}"
                                          onmouseenter="showNoteTooltip(this)"
                                          onmouseleave="hideNoteTooltip()">{{ $plan->name }}</span>
                                    <!-- Red triangle indicator (Excel-style) -->
                                    <span class="absolute top-0 right-0 -mt-1 -mr-2 w-0 h-0 border-t-[7px] border-r-[7px] border-t-transparent border-r-red-500 pointer-events-none"></span>
                                </div>
                            @else
                                {{ $plan->name }}
                            @endif
                        </td>

                        <td class="px-3 md:px-6 py-4">
                            {{ $plan->deadline_type }}
                        </td>

                        <td class="px-3 md:px-6 py-4">
                            @if($plan->employee_name)
                                {{ $plan->employee_name }}{{ $plan->location_name ? ' - ' . $plan->location_name : '' }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-3 md:px-6 py-4">
                            @if(is_null($plan->training_date) && in_array($plan->deadline_type, ['Training Plan', 'Course']))
                                <span class="text-gray-400 italic text-xs">{{ __('lang.to_be_scheduled') }}</span>
                            @else
                                {{ \Carbon\Carbon::parse($plan->expiry_date)->format('d F Y') }}
                            @endif
                        </td>

                        <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="javascript:void(0)"
                                    onclick="openRenewalModal({{ $plan->id }}, '{{ $currentCompany->name }}', '{{ $plan->name }}', '{{ $plan->first_name ?? '' }}', '{{ $plan->surname ?? '' }}', '{{ $plan->deadline_type }}')"
                                    class="font-medium text-[#0C3183] p-2 cursor-pointer hover:underline">
                                    {{ __('lang.renew') }}
                                </a>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">
                            {{ __('lang.no_data_available') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Renewal Modal -->
    <div id="renewalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sync-alt text-[#0C3183]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900" id="modalCompanyName"></h3>
                </div>
                <button onclick="closeRenewalModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mb-6">
                <p class="text-gray-700 text-sm mb-4" id="modalCourseWorkerInfo"></p>

                <form id="renewalForm">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="deadline_type" name="deadline_type">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('lang.renewal_date') }}
                        </label>
                        <input type="date" id="renewal_date" name="renewal_date" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeRenewalModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                            {{ __('lang.close') }}
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                            {{ __('lang.renews') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('filterForm');
            const operatingLocation = document.getElementById('operatingLocation');
            const deadlineTypeFilter = document.getElementById('deadline-type-filter');
            const search = document.getElementById('search');
            const resetFilter = document.getElementById('resetFilter');

            function submitForm() {
                filterForm.submit();
            }

            if (operatingLocation) operatingLocation.addEventListener('change', submitForm);
            if (deadlineTypeFilter) deadlineTypeFilter.addEventListener('change', submitForm);
            if (search) search.addEventListener('change', submitForm);

            if (resetFilter) {
                resetFilter.addEventListener('click', function() {
                    window.location.href = "{{ route('admin.deadlines') }}";
                });
            }
        });

        // Renewal Modal Functions
        function openRenewalModal(planId, companyName, courseName, workerFirstName, workerSurname, deadlineType) {
            console.log(planId, companyName, courseName, workerFirstName, workerSurname, deadlineType);
            document.getElementById('id').value = planId;
            document.getElementById('deadline_type').value = deadlineType;
            document.getElementById('modalCompanyName').textContent = companyName;

            let info = courseName;
            if (workerFirstName || workerSurname) {
                info += ' - ' + (workerFirstName || '') + ' ' + (workerSurname || '');
            }
            document.getElementById('modalCourseWorkerInfo').textContent = info;

            document.getElementById('renewalModal').classList.remove('hidden');
        }

        function closeRenewalModal() {
            document.getElementById('renewalModal').classList.add('hidden');
            document.getElementById('renewalForm').reset();
        }

        // Handle renewal form submission
        document.getElementById('renewalForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('admin.training-plan.renew') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error renewing plan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while renewing.');
                });
        });
    </script>
@endsection
