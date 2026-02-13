@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Dettagli Profilo SMTP</h1>
            <div class="flex gap-2">
                @can('edit smtp-profiles')
                <a href="{{ route('admin.smtp-profiles.edit', $smtpProfile->id) }}"
                    class="bg-[#0C3183] text-white px-6 py-2.5 rounded-lg hover:bg-[#0a2766]">
                    Modifica
                </a>
                @endcan
                <a href="{{ route('admin.smtp-profiles.index') }}"
                    class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                    Indietro
                </a>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

            <!-- Row 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nome Profilo</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Host SMTP</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->host }}</p>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Porta</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->port }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Crittografia</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->encryption ? strtoupper($smtpProfile->encryption) : 'Nessuna' }}</p>
                </div>
            </div>

            <!-- Row 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Username</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->username }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Password</label>
                    <p class="text-base text-gray-900">••••••••</p>
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Indirizzo mittente (From Address)</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->from_address ?: '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Nome mittente (From Name)</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->from_name ?: '-' }}</p>
                </div>
            </div>

            <!-- Row 5 -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-500 mb-1">Reply-To</label>
                <p class="text-base text-gray-900">{{ $smtpProfile->reply_to ?: '-' }}</p>
            </div>

            <!-- Row 6 - Timestamps -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Creato il</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Ultimo aggiornamento</label>
                    <p class="text-base text-gray-900">{{ $smtpProfile->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

        </div>
    </div>
@endsection
