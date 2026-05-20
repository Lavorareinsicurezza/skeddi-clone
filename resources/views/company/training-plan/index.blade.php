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
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.training_plan') }}</h1>

        <div class="flex items-center gap-3">
            @can('view training-plan')
            <a href="{{ route('admin.training-plan.export') }}"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-900 hover:bg-gray-50 font-medium">
                <i class="fa fa-download mr-2"></i>
                {{ __('lang.export') }}
            </a>
            @endcan
            @can('view training-plan-edit')
            <!-- Edit Button (Hidden in edit mode) -->
            <button type="button" id="editButton" onclick="toggleEditMode()"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                <i class="fa fa-edit mr-2"></i>
                {{ __('lang.edit') }}
            </button>

            <!-- Save and Cancel Buttons (Hidden in view mode) -->
            <div id="editButtons" class="hidden flex items-center gap-3">
                <button type="button" onclick="cancelEdit()"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-900 hover:bg-gray-50 font-medium">
                    {{ __('lang.cancel') }}
                </button>
                <button type="button" onclick="saveTrainingPlan()"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    <i class="fa fa-save mr-2"></i>
                    {{ __('lang.save') }}
                </button>
            </div>
            @endcan
        </div>
    </div>

    <!-- Training Plan Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <form id="trainingPlanForm" action="{{ route('admin.training-plan.save') }}" method="POST">
            @csrf
            <table class=" min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-blue-600 border-b">
                        <!-- Static Columns -->
                        <th scope="col"
                            class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider sticky left-0 bg-blue-600 z-10">
                            {{ __('lang.employee') }}
                        </th>
                        <th scope="col"
                            class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider bg-blue-600">
                            {{ __('lang.job_title') }}
                        </th>
                        <th scope="col"
                            class="px-4 py-4 text-left text-xs font-bold text-white uppercase tracking-wider bg-blue-600 whitespace-nowrap">
                            {{ __('lang.operating_location') }}
                        </th>

                        <!-- Dynamic Course Type Columns -->
                        @foreach ($courseTypes as $courseType)
                            <th scope="col"
                                class="px-4 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-blue-600"
                                colspan="2">
                                {{ $courseType->name }}
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-blue-500 border-b">
                        <th colspan="3"></th>
                        <!-- Sub-headers for each course type -->
                        @foreach ($courseTypes as $courseType)
                            <th class="px-2 py-2 text-xs font-bold text-white uppercase">
                                {{ __('lang.training') }}
                            </th>
                            <th class="px-2 py-2 text-xs font-bold text-white uppercase">
                                {{ __('lang.expiration') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($workers as $worker)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Employee Name -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-bold sticky left-0 bg-white">
                                {{ $worker->first_name }} <br />{{ $worker->surname }}
                            </td>

                            <!-- Job Title -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $worker->job_title ?? '-' }}
                            </td>

                            <!-- Operating Location -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                {{ $worker->operatingLocation?->name ?? '-' }}
                            </td>

                            <!-- Dynamic Course Data -->
                            @foreach ($courseTypes as $courseType)
                                @php
                                    $key = $worker->id . '_' . $courseType->id;
                                    $record = $trainingRecords->get($key);
                                    $trainingDate = $record ? $record->training_date?->format('d/m/Y') : '';
                                    $expirationDate = $record ? $record->expiration_date?->format('d/m/Y') : '';
                                    $toBeScheduled = $record ? $record->to_be_scheduled : false;

                                    // Unique row id for hide/show
                                    $rowId = "row-" . $worker->id . "-" . $courseType->id;
                                    $trainingInputId = "training-" . $worker->id . "-" . $courseType->id;
                                    $expirationInputId = "expiration-" . $worker->id . "-" . $courseType->id;
                                @endphp

                                <!-- TRAINING + EXPIRATION MERGED LAYOUT -->
                                <td colspan="2" class="px-2 py-2 text-sm text-gray-900 text-center border-l border-gray-200">

                                    <!-- -------------------- VIEW MODE -------------------- -->
                                    <div class="view-mode">
                                        @if ($toBeScheduled && !$trainingDate)
                                            @php
                                                $notes = $record ? $record->documents->whereNotNull('note')->where('note', '!=', '')->pluck('note') : collect();
                                            @endphp
                                            <div class="flex items-center justify-center gap-2">
                                                <span class="text-xs text-gray-600 font-medium">{{ __('lang.to_be_scheduled') }}</span>
                                                <div class="flex items-center text-[#0C3183] justify-center">
                                                    @if($notes->isNotEmpty())
                                                        <div class="relative inline-block">
                                                            <i class="fa fa-file-lines cursor-pointer hover:text-[#0A2869] document-icon"
                                                               data-worker-id="{{ $worker->id }}"
                                                               data-course-type-id="{{ $courseType->id }}"
                                                               data-worker-name="{{ $worker->first_name }} {{ $worker->surname }}"
                                                               data-course-name="{{ $courseType->name }}"
                                                               data-notes="{{ json_encode($notes->values()->all()) }}"
                                                               onmouseenter="showNoteTooltip(this)"
                                                               onmouseleave="hideNoteTooltip()"
                                                               onclick="openDocumentModal(this)"></i>
                                                            <span class="absolute top-0 right-0 -mt-1 -mr-2 w-0 h-0 border-t-[7px] border-r-[7px] border-t-transparent border-r-red-500 pointer-events-none"></span>
                                                        </div>
                                                    @else
                                                        <i class="fa fa-file-lines cursor-pointer hover:text-[#0A2869] document-icon"
                                                           data-worker-id="{{ $worker->id }}"
                                                           data-course-type-id="{{ $courseType->id }}"
                                                           data-worker-name="{{ $worker->first_name }} {{ $worker->surname }}"
                                                           data-course-name="{{ $courseType->name }}"
                                                           onclick="openDocumentModal(this)"></i>
                                                    @endif
                                                </div>
                                            </div>

                                        @elseif($trainingDate || $expirationDate)
                                            @php
                                                if (!$expirationDate)
                                                    $expirationClass = 'bg-gray-100 text-gray-600';
                                                else {
                                                    $expDate = $record->expiration_date;
                                                    $now = \Carbon\Carbon::now();
                                                    $daysUntilExpiry = $now->diffInDays($expDate, false);

                                                    if ($daysUntilExpiry < 0)
                                                        $expirationClass = 'bg-red-200 text-red-700 font-bold';
                                                    elseif ($daysUntilExpiry <= 30)
                                                        $expirationClass = 'bg-yellow-200 text-orange-700 font-bold';
                                                    else
                                                        $expirationClass = 'bg-green-200 text-green-700 font-bold';
                                                }
                                            @endphp

                                            <div class="flex items-center justify-center gap-1">

                                                <!-- Training -->
                                                <div class="flex items-center justify-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-medium">
                                                    <span>{{ $trainingDate ?: '-' }}</span>
                                                </div>

                                                <!-- Expiration -->
                                                <div class="flex items-center justify-center gap-1 px-2 py-1 rounded text-xs {{ $expirationClass }}">
                                                    <span>{{ $expirationDate ?: '-' }}</span>
                                                </div>

                                                <div class="flex items-center text-[#0C3183] pt-5 justify-center gap-1">
                                                    @php
                                                        $notes = $record ? $record->documents->whereNotNull('note')->where('note', '!=', '')->pluck('note') : collect();
                                                    @endphp
                                                    @if($notes->isNotEmpty())
                                                        <div class="relative inline-block">
                                                            <i class="fa fa-file-lines cursor-pointer hover:text-[#0A2869] document-icon"
                                                               data-worker-id="{{ $worker->id }}"
                                                               data-course-type-id="{{ $courseType->id }}"
                                                               data-worker-name="{{ $worker->first_name }} {{ $worker->surname }}"
                                                               data-course-name="{{ $courseType->name }}"
                                                               data-notes="{{ json_encode($notes->values()->all()) }}"
                                                               onmouseenter="showNoteTooltip(this)"
                                                               onmouseleave="hideNoteTooltip()"
                                                               onclick="openDocumentModal(this)"></i>
                                                            <span class="absolute top-0 right-0 -mt-1 -mr-2 w-0 h-0 border-t-[7px] border-r-[7px] border-t-transparent border-r-red-500 pointer-events-none"></span>
                                                        </div>
                                                    @else
                                                        <i class="fa fa-file-lines cursor-pointer hover:text-[#0A2869] document-icon"
                                                           data-worker-id="{{ $worker->id }}"
                                                           data-course-type-id="{{ $courseType->id }}"
                                                           data-worker-name="{{ $worker->first_name }} {{ $worker->surname }}"
                                                           data-course-name="{{ $courseType->name }}"
                                                           onclick="openDocumentModal(this)"></i>
                                                    @endif
                                                </div>
                                            </div>

                                        @else
                                            <span class="text-gray-400 font-medium">-</span>
                                        @endif
                                    </div>

                                    <!-- -------------------- EDIT MODE -------------------- -->
                                    <div class="edit-mode hidden">
                                        <div class="flex flex-col items-center gap-2">

                                            <!-- Checkbox Center -->
                                            <label class="flex items-center gap-1 text-xs text-gray-900 font-medium justify-center">
                                                <input type="checkbox" class="toBeScheduledCheckbox" value="1"
                                                    data-target="{{ $rowId }}"
                                                    name="records[{{ $worker->id }}_{{ $courseType->id }}][to_be_scheduled]" {{ $toBeScheduled ? 'checked' : '' }}>
                                                <span>{{ __('lang.to_be_scheduled') }}</span>
                                            </label>

                                            <!-- Dates Row (Training + Expiration side-by-side) -->
                                            <div id="{{ $rowId }}" class="flex items-center justify-center gap-2 dateInputs">

                                                <!-- Training Date -->
                                                <input type="date"
                                                    id="{{ $trainingInputId }}"
                                                    name="records[{{ $worker->id }}_{{ $courseType->id }}][training_date]"
                                                    value="{{ $record ? $record->training_date?->format('Y-m-d') : '' }}"
                                                    class="training-date-input px-2 py-1 text-xs border-0 border-b-2 border-gray-300"
                                                    data-validity-years="{{ $courseType->validity_years ?? 0 }}"
                                                    data-expiration-target="{{ $expirationInputId }}">

                                                <!-- Expiration Date -->
                                                <input type="date"
                                                    id="{{ $expirationInputId }}"
                                                    name="records[{{ $worker->id }}_{{ $courseType->id }}][expiration_date]"
                                                    value="{{ $record ? $record->expiration_date?->format('Y-m-d') : '' }}"
                                                    class="px-2 py-1 text-xs border-0 border-b-2 border-gray-300">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden fields -->
                                    <input type="hidden" name="records[{{ $worker->id }}_{{ $courseType->id }}][worker_id]"
                                        value="{{ $worker->id }}">
                                    <input type="hidden" name="records[{{ $worker->id }}_{{ $courseType->id }}][course_type_id]"
                                        value="{{ $courseType->id }}">
                                </td>
                            @endforeach

                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + ($courseTypes->count() * 2) }}" class="px-6 py-12 text-center text-gray-600 font-medium">
                                {{ __('lang.no_data_available') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

    <!-- Document Management Modal -->
    <div id="documentModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-8 border w-full max-w-2xl shadow-lg bg-white rounded-lg">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900" id="modalTitle">
                    {{ __('lang.document_management_for_deadline') }}
                </h3>
                <button onclick="closeDocumentModal()" class="text-gray-400 hover:text-red-600 text-2xl font-bold transition">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="documentForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="modal_worker_id" name="worker_id">
                <input type="hidden" id="modal_course_type_id" name="course_type_id">

                <!-- Add Note -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        {{ __('lang.add_note') }}
                    </label>
                    <textarea name="note" id="document_note" rows="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900"
                        placeholder="{{ __('lang.add_note') }}"></textarea>
                </div>

                <!-- Add New Document -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        {{ __('lang.add_new_document') }}
                    </label>
                    <div class="relative">
                        <input type="text" id="modal_document_name" readonly
                            class="w-full px-4 py-2.5 pr-20 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                            placeholder="{{ __('lang.add_new_document') }}">
                        <button type="button" onclick="document.getElementById('document_file').click()"
                            class="absolute right-2 top-1/2 mt-4 -translate-y-1/2 px-4 py-1.5 bg-gray-200 text-[#0C3183] rounded-md hover:bg-gray-300 text-sm font-medium">
                            {{ __('lang.browse') }}
                        </button>
                        <input type="file" name="file" id="document_file" class="hidden" onchange="updateDocumentFileName(this)">
                    </div>
                </div>

                <!-- Existing Documents List -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-900 mb-3">{{ __('lang.documents') }}</h4>
                    <div id="documentsList" class="space-y-2 max-h-60 overflow-y-auto">
                        <!-- Documents will be loaded here -->
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDocumentModal()"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-900 hover:bg-gray-50 font-medium">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        {{ __('lang.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        let isEditMode = false;

        function getDownloadUrl(id) {
            return "{{ route('admin.training-plan.documents.download', ['id' => '__ID__']) }}".replace('__ID__', id);
        }

        function getDeleteUrl(id) {
            return "{{ route('admin.training-plan.documents.delete', ['id' => '__ID__']) }}".replace('__ID__', id);
        }

        function updateDocumentFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : '';
            document.getElementById('modal_document_name').value = fileName;
        }

        function toggleEditMode() {
            isEditMode = true;
            document.getElementById('editButton').classList.add('hidden');
            document.getElementById('editButtons').classList.remove('hidden');

            // Show all edit mode elements and hide view mode elements
            document.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll('.view-mode').forEach(el => el.classList.add('hidden'));
        }

        function cancelEdit() {
            isEditMode = false;
            document.getElementById('editButton').classList.remove('hidden');
            document.getElementById('editButtons').classList.add('hidden');

            // Hide all edit mode elements and show view mode elements
            document.querySelectorAll('.edit-mode').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.view-mode').forEach(el => el.classList.remove('hidden'));

            // Reload page to reset form
            window.location.reload();
        }

        function saveTrainingPlan() {
            if (confirm('{{ __('lang.save_training_plan_confirm') }}')) {
                document.getElementById('trainingPlanForm').submit();
            }
        }

        // Calculate expiration date based on training date and validity years
        function calculateExpirationDate(trainingDate, validityYears) {
            if (!trainingDate || !validityYears || validityYears == 0) {
                return '';
            }

            const date = new Date(trainingDate);
            date.setFullYear(date.getFullYear() + parseInt(validityYears));

            // Format as YYYY-MM-DD for input[type="date"]
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Handle "To Be Scheduled" checkbox
            document.querySelectorAll(".toBeScheduledCheckbox").forEach(chk => {
                function toggleDates() {
                    let targetId = chk.dataset.target;
                    let dateRow = document.getElementById(targetId);
                    if (!dateRow) return;

                    if (chk.checked) {
                        dateRow.classList.add("hidden");
                    } else {
                        dateRow.classList.remove("hidden");
                    }
                }

                chk.addEventListener("change", toggleDates);
                toggleDates(); // run on load
            });

            // Handle automatic expiration date calculation
            document.querySelectorAll('.training-date-input').forEach(input => {
                input.addEventListener('change', function() {
                    const validityYears = this.dataset.validityYears;
                    const expirationTargetId = this.dataset.expirationTarget;
                    const expirationInput = document.getElementById(expirationTargetId);

                    if (expirationInput && this.value) {
                        const expirationDate = calculateExpirationDate(this.value, validityYears);
                        expirationInput.value = expirationDate;
                    }
                });
            });
        });

        // Document Modal Functions
        let currentWorkerId = null;
        let currentCourseTypeId = null;

        function openDocumentModal(element) {
            currentWorkerId = element.dataset.workerId;
            currentCourseTypeId = element.dataset.courseTypeId;
            const workerName = element.dataset.workerName;
            const courseName = element.dataset.courseName;

            document.getElementById('modal_worker_id').value = currentWorkerId;
            document.getElementById('modal_course_type_id').value = currentCourseTypeId;
            document.getElementById('modalTitle').textContent =
                '{{ __('lang.document_management_for_deadline') }} - ' + workerName + ' - ' + courseName;

            loadDocuments();
            document.getElementById('documentModal').classList.remove('hidden');
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.add('hidden');
            document.getElementById('documentForm').reset();
            document.getElementById('modal_document_name').value = '';
            currentWorkerId = null;
            currentCourseTypeId = null;
            const documentsList = document.getElementById('documentsList');
            documentsList.innerHTML = `
                        <p class="text-sm text-gray-500 text-center py-4">
                            {{ __('lang.no_documents_available') }}
                        </p>
                    `;
        }

        function loadDocuments() {
            fetch('{{ route('admin.training-plan.documents.get') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    worker_id: currentWorkerId,
                    course_type_id: currentCourseTypeId
                })
            })
            .then(response => response.json())
            .then(data => {
                const documentsList = document.getElementById('documentsList');

                if (data.length === 0) {
                    documentsList.innerHTML = `
                        <p class="text-sm text-gray-500 text-center py-4">
                            {{ __('lang.no_documents_available') }}
                        </p>
                    `;
                } else {
                    documentsList.innerHTML = data.map(doc => `
                        <div class="flex items-start justify-between p-3 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                            <div class="flex-1">
                                ${doc.file_name ? `
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fa fa-file text-blue-600"></i>
                                        <a href="${getDownloadUrl(doc.id)}"
                                           class="text-sm font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                            ${doc.file_name}
                                        </a>
                                    </div>
                                ` : ''}
                                ${doc.note ? `
                                    <p class="text-sm text-gray-900 font-medium mt-1">${doc.note}</p>
                                ` : ''}
                                <p class="text-xs text-gray-600 mt-1">
                                    {{ __('lang.uploaded_on') }} ${new Date(doc.created_at).toLocaleDateString()}
                                </p>
                            </div>
                            <button type="button" onclick="deleteDocument(${doc.id})"
                                class="text-red-600 hover:text-red-800 ml-3">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `).join('');
                }
            })
            .catch(error => {
                console.error('Error loading documents:', error);
            });
        }

        function deleteDocument(documentId) {
            if (!confirm('{{ __('lang.delete_document_confirm') }}')) {
                return;
            }

            fetch(getDeleteUrl(documentId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadDocuments();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error deleting document:', error);
                alert('{{ __('lang.error') }}');
            });
        }

        // Handle form submission
        document.getElementById('documentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('admin.training-plan.documents.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset form fields
                    document.getElementById('document_note').value = '';
                    document.getElementById('document_file').value = '';
                    document.getElementById('modal_document_name').value = '';

                    // Reload documents list
                    loadDocuments();

                    // Update icon's data-notes attribute with new notes
                    if (data.notes && data.notes.length > 0) {
                        const icon = document.querySelector(`i[data-worker-id="${currentWorkerId}"][data-course-type-id="${currentCourseTypeId}"]`);
                        if (icon) {
                            icon.setAttribute('data-notes', JSON.stringify(data.notes));
                        }
                    }

                    // Close modal
                    closeDocumentModal();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error saving document:', error);
                alert('{{ __('lang.error') }}');
            });
        });

    </script>
@endsection
