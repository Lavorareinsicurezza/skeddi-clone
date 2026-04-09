@extends('layouts.app')

@section('content')

    <!-- Header Section -->
    <div class="mb-4 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-3">

        <!-- Heading -->
        <h1 class="text-lg md:text-xl font-semibold text-gray-900 whitespace-nowrap">
            {{ __('lang.hello') }}, {{ $currentCompany->name ?? 'Guest' }}
        </h1>

        <!-- Send Notification Button (hidden by default) -->
        <div id="sendNotificationContainer" class="hidden flex items-center space-x-2">
            <button id="openSendNotificationBtn" type="button"
                class="px-4 py-2 bg-[#0C3183] text-white rounded-lg shadow-sm text-sm font-medium hover:bg-[#0A2869]">
                Send Email
            </button>
        </div>

        <!-- Filter Form -->
        <form id="filterForm" method="GET" action="{{ route('admin.dashboard') }}"
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
                <div
                    class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    {{-- <label for="toDate" class="text-gray-600 font-medium whitespace-nowrap">{{ __('lang.to')
                        }}:</label> --}}
                    <select name="deadline_type" id="deadline-type-filter"
                        class="bg-transparent border-0 border-b border-gray-300 px-1 py-0.5 text-gray-600 font-medium focus:outline-none w-full sm:w-auto text-xs">
                        <option value="all">{{ __('lang.all') }}</option>
                        <option value="training_plan" {{ request('deadline_type') == 'training_plan' ? 'selected': '' }}>{{ __('lang.training_plan') }}</option>
                        <option value="visits" {{ request('deadline_type') == 'visits' ? 'selected': '' }}>{{ __('lang.visit_type') }}</option>
                        <option value="documents" {{ request('deadline_type') == 'documents' ? 'selected': '' }}>{{ __('lang.documents') }}</option>
                    </select>

                </div>
                <div
                    class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
                    {{-- <label for="toDate" class="text-gray-600 font-medium whitespace-nowrap">{{ __('lang.to')
                        }}:</label> --}}
                    <input type="text" name="search" id="search"
                        class="bg-transparent border-0 border-b border-gray-300 px-1 py-0.5 text-gray-600 font-medium focus:outline-none w-full sm:w-auto text-xs"
                    placeholder="{{ __('lang.search') }}" value="{{ request('search') }}">
                </div>
                <div
                    class="px-3 py-2 flex items-center space-x-2 sm:border-r border-b sm:border-b-0 border-gray-200 text-xs">
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

                <!-- Reset Filter -->
                <div class="px-3 py-2 flex justify-center sm:justify-start items-center space-x-3">
                    <button type="button" id="resetFilter"
                        class="flex items-center text-red-600 cursor-pointer hover:text-red-700 text-xs font-medium space-x-1">
                        <i class="fas fa-rotate-left w-3 h-3"></i>
                        <span>{{ __('lang.reset') }}</span>
                    </button>
                    <a href="{{ route('admin.deadlines.export', request()->query()) }}"
                        class="flex items-center text-green-600 cursor-pointer hover:text-green-700 text-xs font-medium space-x-1">
                        <i class="fas fa-file-excel w-3 h-3"></i>
                        <span>{{ __('lang.export') }}</span>
                    </a>
                </div>

            </div>
        </form>
    </div>



    <!-- Table Section -->
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-900 bg-white">
            <thead class="text-xs text-white uppercase bg-blue-600 border-b">
                <tr>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap font-bold">
                        <input type="checkbox" id="masterCheckbox" title="Select all">
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap font-bold">
                        {{ __('lang.company_name') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap font-bold">
                        {{ __('lang.deadline_type') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap font-bold">
                        {{ __('lang.employee_name') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap font-bold">
                        {{ __('lang.expiry_date') }}
                    </th>
                    <th scope="col" class="px-3 md:px-6 py-3 whitespace-nowrap font-bold">
                        {{ __('lang.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($records as $plan)
                    @php
                        $dt = strtolower(trim($plan->deadline_type));
                        $moduleKey = match($dt) {
                            'training plan' => 'training_plan',
                            'course' => 'course',
                            'document' => 'document',
                            'visit type' => 'visit',
                            default => Str::slug($dt, '_'),
                        };
                    @endphp
                    <tr class="bg-white border-b">
                        <td class="px-3 md:px-6 py-4">
                            <input type="checkbox" class="recordCheckbox" data-id="{{ $plan->id }}" data-module="{{ $moduleKey }}">
                        </td>

                        <td class="px-3 md:px-6 py-4">
                            {{ $plan->company_name }}
                        </td>

                        <td class="px-3 md:px-6 py-4">
                            {{ $plan->name }}
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
                                <span class="text-gray-600 italic text-xs font-medium">{{ __('lang.to_be_scheduled') }}</span>
                            @else
                                <span class="font-bold">{{ \Carbon\Carbon::parse($plan->expiry_date)->format('d F Y') }}</span>
                            @endif
                        </td>

                        <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-2">
                                <a href="javascript:void(0)"
                                    onclick="openRenewalModal({{ $plan->id }}, '{{ $plan->company?->name ?? null}}', '{{ $plan->name }}', '{{ $plan->worker?->first_name ?? null }}', '{{ $plan->worker?->surname ?? null }}', '{{ $plan->deadline_type }}')"
                                    class="font-bold text-blue-600 p-2 cursor-pointer hover:text-blue-800 hover:underline">
                                    {{ __('lang.renew') }}
                                </a>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-600 font-medium">
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
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-sync-alt text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900" id="modalCompanyName"></h3>
                </div>
                <button onclick="closeRenewalModal()" class="text-gray-400 hover:text-red-600 transition">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="mb-6">
                <p class="text-gray-900 text-sm mb-4 font-medium" id="modalCourseWorkerInfo"></p>

                <form id="renewalForm">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="deadline_type" name="deadline_type">

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-900 mb-2">
                            {{ __('lang.renewal_date') }}
                        </label>
                        <input type="date" id="renewal_date" name="renewal_date" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeRenewalModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-900 hover:bg-gray-50 font-medium">
                            {{ __('lang.close') }}
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold">
                            {{ __('lang.renews') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Send Email Modal -->
    <div id="sendEmailModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-6 border w-full max-w-2xl shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Send Email</h3>
                <button onclick="closeSendEmailModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fa fa-times text-xl"></i>
                </button>
            </div>

            <form id="sendEmailForm">
                @csrf
                <input type="hidden" id="selectedItems" name="items">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Subject</label>
                    <input type="text" id="emailSubject" name="subject" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Body</label>

                    <div class="w-full border border-gray-300 rounded-lg bg-white">
                        <!-- Toolbar -->
                        <div class="px-3 py-2 border-b bg-gray-50">
                            <div class="flex flex-wrap items-center gap-1">
                                <button type="button" id="emailBoldBtn" title="Bold"
                                    class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 4v12h4.5a4.5 4.5 0 003.256-7.606A4 4 0 0011 4H6zm2 2h3a2 2 0 110 4H8V6zm0 6h4a2.5 2.5 0 110 5H8v-5z" />
                                    </svg>
                                </button>
                                <button type="button" id="emailItalicBtn" title="Italic"
                                    class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 4v3h2.21l-3.42 8H6v3h8v-3h-2.21l3.42-8H18V4z" />
                                    </svg>
                                </button>
                                <button type="button" id="emailUnderlineBtn" title="Underline"
                                    class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 17a5 5 0 01-5-5V4h2v8a3 3 0 006 0V4h2v8a5 5 0 01-5 5zm-8 1h16v2H2z" />
                                    </svg>
                                </button>
                                <div class="w-px h-6 bg-gray-300 mx-1"></div>
                                <button type="button" id="emailLinkBtn" title="Insert Link"
                                    class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" />
                                    </svg>
                                </button>
                                <div class="w-px h-6 bg-gray-300 mx-1"></div>
                                <button type="button" id="emailUlBtn" title="Unordered List"
                                    class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                                    </svg>
                                </button>
                                <button type="button" id="emailOlBtn" title="Ordered List"
                                    class="p-2 text-gray-600 rounded hover:text-gray-900 hover:bg-gray-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-white rounded-b-lg">
                            <div id="emailEditor" contenteditable="true"
                                class="block w-full px-0 text-sm text-gray-800 bg-white border-0 focus:ring-0 focus:outline-none min-h-[200px]"></div>
                            <textarea id="emailBody" name="body" class="hidden"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeSendEmailModal()"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Close</button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">Send</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filterForm');
            const statusFilter = document.getElementById('statusFilter');
            const fromDate = document.getElementById('fromDate');
            const toDate = document.getElementById('toDate');
            const deadlineTypeFilter = document.getElementById('deadline-type-filter');
            const search = document.getElementById('search');
            const resetFilter = document.getElementById('resetFilter');
            const scheduledInput = document.getElementById('scheduledInput');
            const operatingLocation = document.getElementById('operatingLocation');

            // Checkbox handling
            const masterCheckbox = document.getElementById('masterCheckbox');
            const recordCheckboxes = Array.from(document.querySelectorAll('.recordCheckbox'));
            const sendNotificationContainer = document.getElementById('sendNotificationContainer');
            const openSendNotificationBtn = document.getElementById('openSendNotificationBtn');
            const sendEmailModal = document.getElementById('sendEmailModal');
            const selectedItemsInput = document.getElementById('selectedItems');
            const emailEditor = document.getElementById('emailEditor');
            const emailBodyInput = document.getElementById('emailBody');
            const sendEmailForm = document.getElementById('sendEmailForm');

            function updateSendButtonVisibility() {
                const checkedCount = recordCheckboxes.filter(cb => cb.checked).length;
                sendNotificationContainer.classList.toggle('hidden', checkedCount === 0);
            }

            if (masterCheckbox) {
                masterCheckbox.addEventListener('change', function () {
                    recordCheckboxes.forEach(cb => {
                        cb.checked = this.checked;
                    });
                    updateSendButtonVisibility();
                });
            }

            recordCheckboxes.forEach(cb => {
                cb.addEventListener('change', function () {
                    updateSendButtonVisibility();
                    // Update master checkbox state
                    if (masterCheckbox) {
                        const allChecked = recordCheckboxes.every(checkbox => checkbox.checked);
                        const someChecked = recordCheckboxes.some(checkbox => checkbox.checked);
                        masterCheckbox.checked = allChecked;
                        masterCheckbox.indeterminate = someChecked && !allChecked;
                    }
                });
            });

            openSendNotificationBtn.addEventListener('click', function () {
                const selected = recordCheckboxes.filter(cb => cb.checked).map(el => ({
                    module: el.dataset.module,
                    id: parseInt(el.dataset.id)
                }));

                selectedItemsInput.value = JSON.stringify(selected);
                document.getElementById('emailSubject').value = '';
                emailEditor.innerHTML = '';
                emailBodyInput.value = '';
                sendEmailModal.classList.remove('hidden');
            });

            window.closeSendEmailModal = function () {
                sendEmailModal.classList.add('hidden');
            };

            // Editor functionality
            function execEditorCmd(cmd, val = null) {
                document.execCommand(cmd, false, val);
                emailEditor.focus();
            }

            document.getElementById('emailBoldBtn').addEventListener('click', (e) => {
                e.preventDefault();
                execEditorCmd('bold');
            });
            document.getElementById('emailItalicBtn').addEventListener('click', (e) => {
                e.preventDefault();
                execEditorCmd('italic');
            });
            document.getElementById('emailUnderlineBtn').addEventListener('click', (e) => {
                e.preventDefault();
                execEditorCmd('underline');
            });
            document.getElementById('emailLinkBtn').addEventListener('click', (e) => {
                e.preventDefault();
                const url = prompt('Enter URL:');
                if (url) execEditorCmd('createLink', url);
            });
            document.getElementById('emailUlBtn').addEventListener('click', (e) => {
                e.preventDefault();
                execEditorCmd('insertUnorderedList');
            });
            document.getElementById('emailOlBtn').addEventListener('click', (e) => {
                e.preventDefault();
                execEditorCmd('insertOrderedList');
            });

            emailEditor.addEventListener('input', function () {
                emailBodyInput.value = emailEditor.innerHTML;
            });

            // Handle form submission
            sendEmailForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const items = selectedItemsInput.value ? JSON.parse(selectedItemsInput.value) : [];
                const subject = document.getElementById('emailSubject').value;
                const body = emailBodyInput.value;

                if (!items.length) {
                    alert('No records selected');
                    return;
                }
                if (!subject) {
                    alert('Please enter an email subject');
                    return;
                }

                const payload = { items, subject, body };

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch('{{ route('admin.send-emails') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(payload)
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const results = data.results;
                            const successful = results.filter(r => r.success).length;
                            const failed = results.filter(r => !r.success).length;

                            let message = `Emails sent: ${successful}`;
                            if (failed > 0) {
                                message += `, Failed: ${failed}`;
                            }
                            alert(message);
                            closeSendEmailModal();
                            window.location.reload();
                        } else {
                            alert('Error sending emails');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error sending emails');
                    });
            });

            // Filter form handling
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
            if (deadlineTypeFilter) deadlineTypeFilter.addEventListener('change', submitForm);
            if (search) search.addEventListener('change', submitForm);
            if (operatingLocation) operatingLocation.addEventListener('change', submitForm);

            if (resetFilter) {
                resetFilter.addEventListener('click', function () {
                    scheduledInput.value = 'false';
                    fromDate.value = '';
                    toDate.value = '';
                    search.value = '';
                    if (operatingLocation) operatingLocation.value = '';
                    window.location.href = "{{ route('admin.dashboard') }}";
                });
            }
        });

        // Renewal Modal Functions
        function openRenewalModal(planId, companyName, courseName, workerFirstName, workerSurname, deadlineType) {
            console.log(planId, companyName, courseName, workerFirstName, workerSurname, deadlineType);
            document.getElementById('id').value = planId;
            document.getElementById('deadline_type').value = deadlineType;
            document.getElementById('modalCompanyName').textContent = companyName;
            document.getElementById('modalCourseWorkerInfo').textContent =
                courseName + ' - ' + workerFirstName + ' ' + workerSurname;
            document.getElementById('renewalModal').classList.remove('hidden');
        }

        function closeRenewalModal() {
            document.getElementById('renewalModal').classList.add('hidden');
            document.getElementById('renewalForm').reset();
        }

        // Handle renewal form submission
        document.getElementById('renewalForm').addEventListener('submit', function (e) {
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
                        closeRenewalModal();
                        window.location.reload();
                    } else {
                        alert(data.message || '{{ __('lang.error') }}');
                    }
                })
                .catch(error => {
                    console.error('Error renewing course:', error);
                    alert('{{ __('lang.error') }}');
                });
        });

    </script>
@endsection
