@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Crea Profilo SMTP</h1>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.smtp-profiles.store') }}" method="POST">
            @csrf
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nome Profilo <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Es. Sede Milano" value="{{ old('name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Host SMTP <span class="text-red-500">*</span></label>
                        <input type="text" name="host" placeholder="smtp.example.com" value="{{ old('host') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Porta <span class="text-red-500">*</span></label>
                        <input type="number" name="port" placeholder="587" value="{{ old('port', 587) }}" min="1" max="65535" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Crittografia <span class="text-red-500">*</span></label>
                        <select name="encryption" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="tls" {{ old('encryption') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ old('encryption') == 'none' ? 'selected' : '' }}>Nessuna</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" placeholder="user@example.com" value="{{ old('username') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Row 4 - Optional Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Indirizzo mittente (From Address)</label>
                        <input type="email" name="from_address" placeholder="noreply@example.com" value="{{ old('from_address') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nome mittente (From Name)</label>
                        <input type="text" name="from_name" placeholder="SAFEGEST" value="{{ old('from_name') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Reply-To</label>
                    <input type="email" name="reply_to" placeholder="support@example.com" value="{{ old('reply_to') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.smtp-profiles.index') }}"
                        class="bg-gray-500 text-white px-6 py-2.5 rounded-lg hover:bg-gray-600">
                        Annulla
                    </a>
                    <button type="submit"
                        class="bg-blue-700 text-white px-6 py-2.5 rounded-lg hover:bg-blue-800">
                        Crea
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
