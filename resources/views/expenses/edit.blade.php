@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Expense</h1>

    <form action="{{ route('expenses.update', $expense) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label>Description</label>
            <input type="text" name="description" class="form-control" value="{{ $expense->description }}" required>
        </div>

        <div class="mb-3">
            <label>Amount</label>
            <input type="number" name="amount" class="form-control" step="0.01" value="{{ $expense->amount }}" required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" value="{{ $expense->category }}" required>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ $expense->date }}" required>
        </div>

        <button class="btn btn-primary">Update Expense</button>
    </form>
</div>
@endsection
