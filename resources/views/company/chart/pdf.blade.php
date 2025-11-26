<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        table { width: 80%; margin: 20px auto; border-collapse: collapse; }
        th, td { border: none; padding: 10px; text-align: center; }
        th { background-color: #e9edf7; font-weight: bold; }
        .title { text-align: center; font-size: 22px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>

    <!-- Top Title -->
    <div class="title">Organigramma Azienda {{ $company->name}}</div>

    <!-- Employer Table -->
    <table>
        <tr>
            <th>{{ __('lang.employer') }}</th>
        </tr>
        <tr>
            <td>{{ $company->employer }}</td>
        </tr>
    </table>

    <!-- RSPP & Competent Doctor Table -->
    <table>
        <tr>
            <th>RSPP</th>
            <th>{{ __('lang.competent_doctor') }}</th>
        </tr>
        <tr>
            <td>{{ $company->head_of_prevention }}</td>
            <td>{{ $company->company_doctor }}</td>
        </tr>
    </table>

    <!-- First Aid Staff Table -->
    <table>
        <tr>
            <th>{{ __('lang.first_aid_staff') }}</th>
        </tr>
        @foreach ($courseTypeAid->trainingPlanRecords as $record)
        <tr>
            <td>{{ $record->worker->first_name }} {{ $record->worker->surname }}</td>
        </tr>
        @endforeach
    </table>

      <!-- Firefighting Staff Table -->
    @if (!empty($firefighterWorkers))
    <table>
        <tr>
            <th>{{ __('lang.firefighting_staff') }}</th>
        </tr>
        @foreach ($firefighterWorkers as $record)
            @foreach ($record->trainingPlanRecords as $plan)
            <tr>
                <td>{{ $plan->worker->first_name }} {{ $plan->worker->surname }}</td>
            </tr>
            @endforeach
        @endforeach
    </table>
    @endif

</body>
</html>
