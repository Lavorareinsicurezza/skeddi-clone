@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.organizational_chart') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <a href="{{ route('admin.chart.detail') }}"
                class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm flex"
                title="{{ __('lang.create') . ' ' . __('lang.worker') }}">
                <i class="text-2xl fa fa-chart-simple"></i>
            </a>
        </div>
    </div>

    <!-- Container -->
    <div class="w-full p-6 space-y-10">

        <!-- Row: Datore di lavoro & Responsabile -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Datore di lavoro -->
            <div class="space-y-2">
                <p class="font-medium text-gray-700">{{ __('lang.employer') }}</p>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-gray-500">{{ $company->employer }}</div>
            </div>

            <!-- Responsabile -->
            <div class="space-y-2">
                <p class="font-medium text-gray-700">{{ __('lang.head_prevention_protection') }}</p>
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-gray-500">{{ $company->head_of_prevention }}
                </div>
            </div>

        </div>

        <!-- Dottore competente -->
        <div class="space-y-2">
            <p class="font-medium text-gray-700">{{ __('lang.competent_doctor') }}</p>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-gray-500">{{ $company->company_doctor }}</div>
        </div>

        <!-- Addetti al primo soccorso -->
        @if (isset($courseTypeAid->trainingPlanRecords) && $courseTypeAid->trainingPlanRecords->isNotEmpty())
            <div class="space-y-2">
                <p class="font-medium text-gray-700">{{ __('lang.first_aid_staff') }}</p>
                <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100 rounded-xl">
                    @foreach ($courseTypeAid->trainingPlanRecords as $record)
                        <div class="p-4 text-gray-500 grid grid-cols-2">
                            <div>{{ $record->worker->first_name }}</div>
                            <div>{{ $record->worker->surname }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Addetti antincendio -->
        @if (!empty($courseTypeFireFighter) && isset($courseTypeFireFighter[0]->trainingPlanRecords))
            <div class="space-y-2">
                <p class="font-medium text-gray-700">{{ __('lang.firefighting_staff') }}</p>
                <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100 rounded-xl">

                    @foreach ($courseTypeFireFighter as $record)
                        @foreach ($record->trainingPlanRecords as $plan)
                            <div class="p-4 text-gray-500 grid grid-cols-2">
                                <div>{{ $plan->worker->first_name }}</div>
                                <div>{{ $plan->worker->surname }}</div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
