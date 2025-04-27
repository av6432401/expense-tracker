@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Expense Details</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="card-title font-weight-bold">{{ $expense->description }}</h5>
                        <p class="card-text">
                            <strong>Amount:</strong> ₹{{ number_format($expense->amount, 2) }}
                        </p>
                        <p class="card-text">
                            <strong>Category:</strong> {{ $expense->category }}
                        </p>
                        <p class="card-text">
                            <strong>Date:</strong> {{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}
                        </p>
                    </div>
                    
                    <!-- Edit and Delete Buttons (if needed) -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Expenses
                        </a>

                        <!-- Uncomment if you want to allow editing or deleting -->
                        <!-- 
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" class="ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this expense?')">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                        -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
