@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg border border-gray-200 text-center">
        <div class="text-6xl text-red-600 font-bold mb-4">403</div>
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">Access Denied</h1>
        <p class="text-gray-600 mb-6">You do not have permission to access this page.</p>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Go to Dashboard</a>
    </div>
@endsection
