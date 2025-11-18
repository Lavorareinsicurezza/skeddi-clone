@extends('layouts.app')

@section('styles')
    <style>
        @media (max-width: 350px) {
            .custom-350 {
                grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
            }
        }
    </style>
@endsection

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
        <h1 class="text-3xl font-bold text-gray-900">{{ __('lang.documents') }}</h1>

        <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden shadow-sm">
             <a href="{{ route('admin.company-documents.export') }}"
                class=" px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm border-r border-gray-200 flex"
                title="{{ __('lang.export_data') }}">
                <i class="text-gray-500 fa fa-download"></i>
            </a>
           {{-- <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class=" px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm border-r border-gray-200 flex"
                title="{{ __('lang.import_data') }}">
                <i class="text-gray-500 fa fa-upload"></i>
            </button> --}}
            <a href="{{ route('admin.company-documents.create') }}"
                class=" px-5 py-3 font-semibold text-gray-500 hover:text-[#0C3183] hover:bg-[#EBF1FF] text-sm flex"
                title="{{ __('lang.create') .' '. __('lang.documents') }}">
                <i class="text-gray-500 fa fa-plus"></i>
            </a>
        </div>
    </div>


    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6 custom-350">

            @foreach($documents as $document)
            <!-- Card Item -->
            <div
                class="w-45 bg-white shadow-lg rounded-2xl p-5 relative flex flex-col items-center border border-gray-100 hover:shadow-xl transition-all duration-300">
                <!-- Edit / Delete Icons -->
                <div class="absolute top-3 right-3 flex space-x-0">
                    <a href="{{ route('admin.company-documents.edit', $document->id) }}" class="text-[#0C3183] hover:text-[#0A2869]"> <i class="fa fa-pencil"></i> </a>
                     <form action="{{ route('admin.company-documents.destroy', $document->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('{{ __('lang.delete_document_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-600"
                                        title="{{ __('lang.actions') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                </div>
                <!-- Icon -->
                <div class="mt-4 mb-3 border border-2 border-gray-200 rounded-lg p-3">
                    <img src="{{ asset('assets/images/csv.png') }}" class="h-12 w-12 text-gray-400" alt="">
                </div>
                <!-- Title -->
                <h3 class="text-sm font-semibold text-gray-900 text-center leading-tight">
                    {{ $document->name }}
                </h3>
                <!-- Expiry Date -->
                <p class="text-xs text-[#DC2626] font-medium mt-1 text-center">
                    {{ __('lang.next_deadline') }}: {{ \Carbon\Carbon::parse($document->expiration_date)->format('d M Y') }}
                </p>
            </div>
            @endforeach
        </div>
@endsection
