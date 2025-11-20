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
            <!-- Edit Button (Hidden in edit mode) -->
            <button type="button" id="editButton" onclick="toggleEditMode()"
                class="px-6 py-3 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                <i class="fa fa-edit mr-2"></i>
                {{ __('lang.edit') }}
            </button>

            <!-- Save and Cancel Buttons (Hidden in view mode) -->
            <div id="editButtons" class="hidden flex items-center gap-3">
                <button type="button" onclick="cancelEdit()"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                    {{ __('lang.cancel') }}
                </button>
                <button type="button" onclick="saveTrainingPlan()"
                    class="px-6 py-3 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                    <i class="fa fa-save mr-2"></i>
                    {{ __('lang.save') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Training Plan Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <form id="trainingPlanForm" action="{{ route('admin.training-plan.save') }}" method="POST">
            @csrf
            <table class=" min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr class="border-b">
                        <!-- Static Columns -->
                        <th scope="col"
                            class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider sticky left-0 bg-gray-50 z-10">
                            {{ __('lang.employee') }}
                        </th>
                        <th scope="col"
                            class="px-4 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50">
                            {{ __('lang.job_title') }}
                        </th>

                        <!-- Dynamic Course Type Columns -->
                        @foreach ($courseTypes as $courseType)
                            <th scope="col"
                                class="px-4 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider bg-gray-50"
                                colspan="2">
                                {{ $courseType->name }}
                            </th>
                        @endforeach
                    </tr>
                    <tr class="bg-gray-100 border-b">
                        <th colspan="2"></th>
                        <!-- Sub-headers for each course type -->
                        @foreach ($courseTypes as $courseType)
                            <th class="px-2 py-2 text-xs font-semibold text-gray-600 uppercase">
                                {{ __('lang.training') }}
                            </th>
                            <th class="px-2 py-2 text-xs font-semibold text-gray-600 uppercase">
                                {{ __('lang.expiration') }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($workers as $worker)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Employee Name -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 font-medium sticky left-0 bg-white">
                                {{ $worker->first_name }} <br />{{ $worker->surname }}
                            </td>

                            <!-- Job Title -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $worker->job_title ?? '-' }}
                            </td>

                            <!-- Dynamic Course Data -->
                            @foreach ($courseTypes as $courseType)
                                @php
                                    $key = $worker->id . '_' . $courseType->id;
                                    $record = $trainingRecords->get($key);
                                    $trainingDate = $record ? $record->training_date?->format('d/m/Y') : '';
                                    $expirationDate = $record ? $record->expiration_date?->format('d/m/Y') : '';
                                    $toBeScheduled = $record ? $record->to_be_scheduled : false;
                                @endphp

                                <!-- Training Date Column -->
                                <td class="px-2 py-2 text-sm text-gray-900 text-center border-l border-gray-200">
                                    <!-- View Mode -->
                                    <div class="view-mode">
                                        @if($toBeScheduled && !$trainingDate)
                                            <span class="text-xs text-gray-500">{{ __('lang.to_be_scheduled') }}</span>
                                        @elseif($trainingDate)
                                            <div class="flex items-center justify-center gap-1">
                                                <span>{{ $trainingDate }}</span>
                                                <i class="fa fa-calendar text-xs text-gray-400"></i>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>

                                    <!-- Edit Mode -->
                                    <div class="edit-mode hidden">
                                        <div class="flex flex-col gap-1">
                                            <label class="flex items-center bg-amber-50 gap-1 text-xs text-gray-600">
                                                <input type="checkbox"
                                                    name="records[{{ $worker->id }}_{{ $courseType->id }}][to_be_scheduled_training]"
                                                    class="rounded" {{ $toBeScheduled && !$trainingDate ? 'checked' : '' }}>
                                                <span>{{ __('lang.to_be_scheduled') }}</span>
                                            </label>
                                            <input type="date"
                                                name="records[{{ $worker->id }}_{{ $courseType->id }}][training_date]"
                                                value="{{ $record ? $record->training_date?->format('Y-m-d') : '' }}"
                                                class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#0C3183]">
                                        </div>
                                    </div>
                                </td>

                                <!-- Expiration Date Column -->
                                <td class="px-2 py-2 text-sm text-gray-900 text-center">
                                    <!-- View Mode -->
                                    <div class="view-mode">
                                        @if($expirationDate)
                                            @php
                                                $expDate = $record->expiration_date;
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
                                            <div class="flex items-center justify-center gap-1 {{ $colorClass }}">
                                                <span>{{ $expirationDate }}</span>
                                                <i class="fa fa-calendar text-xs"></i>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>

                                    <!-- Edit Mode -->
                                    <div class="edit-mode hidden">
                                        <input type="date" name="records[{{ $worker->id }}_{{ $courseType->id }}][expiration_date]"
                                            value="{{ $record ? $record->expiration_date?->format('Y-m-d') : '' }}"
                                            class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-[#0C3183] bg-green-50">
                                    </div>

                                    <!-- Hidden fields for worker and course type -->
                                    <input type="hidden" name="records[{{ $worker->id }}_{{ $courseType->id }}][worker_id]"
                                        value="{{ $worker->id }}">
                                    <input type="hidden" name="records[{{ $worker->id }}_{{ $courseType->id }}][course_type_id]"
                                        value="{{ $courseType->id }}">
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + ($courseTypes->count() * 2) }}" class="px-6 py-12 text-center text-gray-500">
                                {{ __('lang.no_data_available') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        let isEditMode = false;

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
    </script>
@endsection
