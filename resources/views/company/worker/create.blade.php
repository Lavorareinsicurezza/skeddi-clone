@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">{{ __('lang.please_fix_following_errors') }}</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            {{ isset($worker) ? __('lang.edit_worker') : __('lang.new_worker') }}
        </h1>
    </div>

    <form
        action="{{ isset($worker) ? route('admin.company-workers.update', $worker->id) : route('admin.company-workers.store') }}"
        method="POST" class="bg-white rounded-lg shadow p-6">
        @csrf
        @if (isset($worker))
            @method('PUT')
        @endif

        <!-- Name and Surname -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.first_name') }}
                </label>
                <input type="text" name="first_name" id="first_name"
                    value="{{ old('first_name', $worker->first_name ?? '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum" required>
            </div>

            <div>
                <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.surname') }}
                </label>
                <input type="text" name="surname" id="surname" value="{{ old('surname', $worker->surname ?? '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum" required>
            </div>
        </div>

        <!-- Job Title and Department -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.job_title') }}
                </label>
                <input type="text" name="job_title" id="job_title"
                    value="{{ old('job_title', $worker->job_title ?? '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum">
            </div>

            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.department') }}
                </label>
                <input type="text" name="department" id="department"
                    value="{{ old('department', $worker->department ?? '') }}"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum">
            </div>
        </div>

        <!-- No longer in force checkbox -->
        <div class="mb-6">
            <label
                class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-[#EBF1FF] max-w-md">
                <span class="text-sm font-medium text-gray-800">{{ __('lang.no_longer_in_force') }}</span>
                <input type="checkbox" name="is_active" id="is_active" value="1"  {{ old('is_active', isset($worker) && !$worker->is_active ? true : false) ? 'checked' : '' }}
                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
            </label>
        </div>

        <!-- Additional Information and Worker Documentation -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="additional_information" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.additional_information') }}
                </label>
                <textarea name="additional_information" id="additional_information" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum">{{ old('additional_information', $worker->additional_information ?? '') }}</textarea>
            </div>

            <div>
                <label for="worker_documentation" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.worker_documentation') }}
                </label>
                <textarea name="worker_documentation" id="worker_documentation" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum">{{ old('worker_documentation', $worker->worker_documentation ?? '') }}</textarea>
            </div>
        </div>

        <!-- PPE and Movement History -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="ppe" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.ppe') }}
                </label>
                <textarea name="ppe" id="ppe" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum">{{ old('ppe', $worker->ppe ?? '') }}</textarea>
            </div>

            <div>
                <label for="movement_history" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.movement_history') }}
                </label>
                <textarea name="movement_history" id="movement_history" rows="3"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                    placeholder="Lorem ipsum">{{ old('movement_history', $worker->movement_history ?? '') }}</textarea>
            </div>
        </div>

        <!-- Training Experience and Workplace Safety Risk -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label for="training_experience" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ __('lang.training_experience') }}
                </label>
                <div class="relative">
                    <input type="text" name="training_experience" id="training_experience"
                        value="{{ old('training_experience', $worker->training_experience ?? '') }}"
                        class="w-full px-4 py-2.5 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                        placeholder="01/07/2020(Exp. 01/07/2020)">
                    <button type="button" onclick="openTrainingModal()"
                        class="absolute right-3 top-9 -translate-y-1/2 text-[#0C3183] hover:text-[#0A2869]">
                        <i class="fa fa-calendar text-lg"></i>
                    </button>
                </div>
            </div>

            <div>
                <label for="workplace_safety_risk" class="block text-sm font-medium text-gray-700 mb-2">
                    &nbsp;
                </label>
                  <label
                class="border border-gray-300 rounded-lg px-4 py-3 flex items-center justify-between cursor-pointer hover:bg-gray-50 has-[:checked]:border-[#0C3183] has-[:checked]:bg-[#EBF1FF] max-w-md">
                <span class="text-sm font-medium text-gray-800">{{ __('lang.workplace_safety_risk') }}</span>
                <input type="checkbox" name="workplace_safety_risk" id="workplace_safety_risk" value="1"
                        {{ old('workplace_safety_risk', isset($worker) && $worker->workplace_safety_risk ? true : false) ? 'checked' : '' }}
                    class="w-6 h-6 appearance-none bg-white border-2 border-gray-400 rounded-full cursor-pointer checked:border-[5px] checked:border-[#0C3183] focus:outline-none focus:ring-2 focus:ring-[#0C3183] focus:ring-offset-2">
            </label>
            </div>
        </div>

        <!-- Medical Visits -->
        <div class="mb-6">
            <label for="medical_visits" class="block text-sm font-medium text-gray-700 mb-2">
                {{ __('lang.medical_visits') }}
            </label>
            <textarea name="medical_visits" id="medical_visits" rows="3"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0C3183] text-sm"
                placeholder="Lorem ipsum">{{ old('medical_visits', $worker->medical_visits ?? '') }}</textarea>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.company-workers.index') }}"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                {{ __('lang.cancel') }}
            </a>
            <button type="submit" class="px-6 py-3 bg-[#0C3183] text-white rounded-lg hover:bg-[#0A2869] font-medium">
                {{ isset($worker) ? __('lang.update_worker') : __('lang.add_worker') }}
            </button>
        </div>
    </form>

    <!-- Training Modal (Optional - for future enhancement) -->
    <div id="trainingModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">{{ __('lang.training_experience') }}</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.training_date') }}</label>
                    <input type="date" id="modal_training_date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('lang.expiry_date') }}</label>
                    <input type="date" id="modal_training_expiry"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#0C3183]">
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeTrainingModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        {{ __('lang.cancel') }}
                    </button>
                    <button type="button" onclick="applyTrainingDates()"
                        class="flex-1 px-4 py-2 bg-[#0C3183] text-white rounded-md hover:bg-[#0A2869]">
                        {{ __('lang.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        function openTrainingModal() {
            document.getElementById('trainingModal').classList.remove('hidden');
        }

        function closeTrainingModal() {
            document.getElementById('trainingModal').classList.add('hidden');
        }

        function applyTrainingDates() {
            const trainingDate = document.getElementById('modal_training_date').value;
            const expiryDate = document.getElementById('modal_training_expiry').value;

            if (trainingDate) {
                const formattedTraining = trainingDate.split('-').reverse().join('/');
                const formattedExpiry = expiryDate ? expiryDate.split('-').reverse().join('/') : '';
                const text = formattedExpiry ?
                    `${formattedTraining}(Exp. ${formattedExpiry})` :
                    formattedTraining;

                document.getElementById('training_experience').value = text;
            }

            closeTrainingModal();
        }
    </script>
@endsection
