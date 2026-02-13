@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flash-message-box" role="alert">
            <span class="block sm:inline">{!! session('error') !!}</span>
        </div>
    @endif

    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">Profili SMTP</h1>
        @can('create smtp-profiles')
        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            <a href="{{ route('admin.smtp-profiles.create') }}"
                class="px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-blue-50 text-sm flex" title="Crea Profilo SMTP">
                <i class="text-gray-500 fa fa-plus"></i>
            </a>
        </div>
        @endcan
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 bg-white">
            <thead class="text-xs text-gray-900 uppercase bg-white border-b">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Nome
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Host
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Porta
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Username
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Azioni
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($smtpProfiles->count() > 0)
                    @foreach ($smtpProfiles as $profile)
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-6 py-4">
                                {{ $profile->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $profile->host }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $profile->port }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $profile->username }}
                            </td>
                            <td class="px-6 py-4">
                                @can('view smtp-profiles')
                                <a href="{{ route('admin.smtp-profiles.show', $profile->id) }}"
                                    class="font-medium text-gray-500 p-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="Visualizza">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                @endcan
                                @can('edit smtp-profiles')
                                <a href="{{ route('admin.smtp-profiles.edit', $profile->id) }}"
                                    class="font-medium text-gray-500 p-2 ml-2 hover:bg-blue-50 border border-gray-200 rounded-[10px]"
                                    title="Modifica">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete smtp-profiles')
                                <form action="{{ route('admin.smtp-profiles.destroy', $profile->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Sei sicuro di voler eliminare questo profilo SMTP?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium text-red-500 p-2 hover:bg-red-50 border border-gray-200 rounded-[10px]"
                                        title="Elimina">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="bg-white border-b border-gray-200">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Nessun dato disponibile
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end">
        {{ $smtpProfiles->onEachSide(1)->links('pagination::tailwind') }}
    </div>

@endsection
