<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Expiry Reminder</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f9f9f9; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" style="background:#ffffff; padding:20px; border-radius:6px;">

                    <tr>
                        <td>
                            <h2 style="color:#0C3183; margin-bottom:10px;">
                                {{ $module }} Expiry Reminder
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px; color:#333;">
                            <p>{{ __('lang.dear_user', ['name' => $record->company->name ?? 'User']) }}</p>

                            @if ($mailType === 'one_month')
                                <p>{{ __('lang.one_month', ['module' => strtolower($module)]) }}</p>
                            @elseif ($mailType === 'one_week')
                                <p>{{ __('lang.one_week', ['module' => strtolower($module)]) }}</p>
                            @elseif ($mailType === 'last_day')
                                <p style="color:#b91c1c;">{{ __('lang.last_day', ['module' => strtolower($module)]) }}
                                </p>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:10px 0;">
                            <table width="100%" cellpadding="6" cellspacing="0" style="border:1px solid #ddd;">
                                <tr>
                                    <td width="30%"><strong>{{ __('lang.name') }}</strong></td>
                                    <td>{{ $record->name ?? $record->companyCourseType->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ __('lang.expiry_date') }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($record->expiration_date ?? $record->expiry_date)->format('d F Y') }}
                                    </td>
                                </tr>
                                @if (isset($record->worker))
                                    <tr>
                                        <td><strong>{{ __('lang.employee') }}</strong></td>
                                        <td>{{ $record->worker->first_name ?? '' }}
                                            {{ $record->worker->surname ?? '' }}</td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:14px; color:#333;">
                            <p>{{ __('lang.recommendation', ['module' => strtolower($module)]) }}</p>
                            <p>{{ __('lang.contact_support') }}</p>
                            <p style="margin-top:20px;">
                                {{ __('lang.best_regards') }}<br>
                                <strong>{{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
