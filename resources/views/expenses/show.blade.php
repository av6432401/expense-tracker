@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Expense Details</h1>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $expense->description }}</h5>
            <p class="card-text"><strong>Amount:</strong> ₹{{ number_format($expense->amount, 2) }}</p>
            <p class="card-text"><strong>Category:</strong> {{ $expense->category }}</p>
            <p class="card-text"><strong>Date:</strong> {{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</p>
        </div>
    </div>

    <a href="{{ route('expenses.index') }}" class="btn btn-secondary">Back to Expenses</a>
</div>
@endsection
