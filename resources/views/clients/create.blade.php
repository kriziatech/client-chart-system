@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">New Project</h1>
        <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-800">&larr; Back to Dashboard</a>
    </div>

    <!-- Error Alert -->
    @if ($errors->any())
    <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl">
        <h4 class="text-sm font-black uppercase tracking-widest text-rose-800 mb-2">Attention: Input Validation Failed
        </h4>
        <ul class="list-disc list-inside text-xs font-bold text-rose-700 space-y-1 uppercase tracking-tight">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('clients.store') }}" method="POST">
        @include('clients._form')
    </form>
</div>
@endsection