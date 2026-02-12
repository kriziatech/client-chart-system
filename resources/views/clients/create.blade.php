@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">New Project</h1>
        <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-800">&larr; Back to Dashboard</a>
    </div>

    <form action="{{ route('clients.store') }}" method="POST">
        @include('clients._form')
    </form>
</div>
@endsection