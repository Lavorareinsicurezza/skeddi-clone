@extends('layouts.app')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.organizational_chart') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <a href="{{ route('admin.chart.pdf') }}"
                class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm flex"
                title="{{ __('lang.create') . ' ' . __('lang.worker') }}">
                <i class="text-2xl fa fa-file"></i>
            </a>
        </div>
    </div>

    <!-- Chart Layout Container -->
    <div class="w-full min-h-screen flex flex-col items-center py-10 space-y-12">

        <!-- Top Center: Datore di lavoro -->
        <div class="bg-[#0C31831A] rounded-md shadow-sm px-10 py-4 text-center w-[360px]">
            <p class="font-semibold text-lg text-gray-700">{{ __('lang.employer') }}</p>
            <p class="text-gray-500">{{ $company->employer }}</p>
        </div>

        <!-- Middle Row: RSPP & Dottore competente -->
        <div class="flex flex-col md:flex-row justify-center items-center gap-[360px]">

            <!-- RSPP -->
            <div class="bg-[#0C31831A] rounded-md shadow-sm px-10 py-4 text-center w-[360px]">
                <p class="font-semibold text-lg text-gray-700">RSPP</p>
                <p class="text-gray-500">{{ $company->head_of_prevention }}</p>
            </div>

            <!-- Dottore competente -->
            <div class="bg-[#0C31831A] rounded-md shadow-sm px-10 py-4 text-center w-[360px]">
                <p class="font-semibold text-lg text-gray-700">{{ __('lang.competent_doctor') }}</p>
                <p class="text-gray-500">{{ $company->company_doctor }}</p>
            </div>

        </div>

        <div class="flex flex-col md:flex-row justify-center items-center gap-[360px]">
            <!-- Bottom: Addetti antincendio -->
            @if (!empty($courseTypeFireFighter) && isset($courseTypeFireFighter[0]->trainingPlanRrecords))
                <div class="bg-[#0C31831A] rounded-md shadow-sm px-10 py-6 text-center w-[360px]">
                    <p class="font-semibold text-lg text-gray-700">{{ __('lang.firefighting_staff') }}</p>

                    <div class="flex flex-col mt-2 space-y-1 text-gray-500">
                        @foreach ($courseTypeFireFighter as $record)
                            @foreach ($record->trainingPlanRecords as $plan)
                                <span>{{ $plan->worker->first_name }} {{ $plan->worker->surname }}</span>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- Bottom: Addetti al primo soccorso -->
            @isset($courseTypeAid->trainingPlanRecords)
                <div class="bg-[#0C31831A] rounded-md shadow-sm px-10 py-6 text-center w-[360px]">
                    <p class="font-semibold text-lg text-gray-700">{{ __('lang.first_aid_staff') }}</p>

                    <div class="flex flex-col mt-2 space-y-1 text-gray-500">
                        @foreach ($courseTypeAid->trainingPlanRecords as $record)
                            <span>{{ $record->worker->first_name }} {{ $record->worker->surname }}</span>
                        @endforeach
                    </div>
                </div>
            @endisset
        </div>

    </div>
@endsection
