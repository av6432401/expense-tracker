@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Expense</h1>

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Description</label>
            <input type="text" name="description" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Amount</label>
            <input type="number" name="amount" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <button class="btn btn-success">Save Expense</button>
    </form>
</div>
@endsection
