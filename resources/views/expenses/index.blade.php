@extends('layouts.app')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
<div class="container bg-light p-4 rounded">
    <h1 class="my-4 text-center">My Expenses</h1>
    
    <!-- CSV Export Button -->
    <a href="{{ route('expenses.export') }}" class="btn btn-success mb-4">Export CSV</a>

    <!-- Add New Expense Button -->
    <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-4">Add New Expense</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Expense Summary -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Expense Summary</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Total Expenses -->
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-info shadow">
                        <div class="card-body">
                            <h5 class="card-title">Total Expenses</h5>
                            <p class="card-text">₹{{ number_format($totalExpense, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Breakdown by Category -->
                <div class="col-md-4 mb-3">
                    <div class="card text-black bg-success shadow">
                        <div class="card-body">
                            <h5 class="card-title">Breakdown by Category</h5>
                            <ul class="list-group">
                                @foreach($categorySummary as $category => $amount)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $category }}
                                        <span class="badge badge-dark">₹{{ number_format($amount, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Breakdown Daywise -->
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-warning shadow">
                        <div class="card-body">
                            <h5 class="card-title">Breakdown Daywise</h5>

                            <!-- Daywise Expense Dropdown -->
                            <div class="form-group">
                                <label for="dateSelect">Select a Date</label>
                                <select id="dateSelect" class="form-control">
                                    <option value="">-- Select a Day --</option>
                                    @foreach($daysWithExpenses as $day)
                                        <option value="{{ $day }}">{{ \Carbon\Carbon::parse($day)->format('d M, Y') }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Display total expenses for the selected day -->
                            <div id="daywiseTotal" class="mb-3"></div>

                            <!-- Expenses of Selected Day -->
                            <div id="daywiseExpenses"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Expense Table -->
    @if($expenses->count())
    <div class="card shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Expense List</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $expense->description }}</td>
                        <td>₹{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->category }}</td>
                        <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M, Y') }}</td>
                        <td>
                            <a href="{{ route('expenses.show', $expense) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
        <div class="alert alert-warning mt-3">
            No expenses found. Start by adding one!
        </div>
    @endif
</div>

<!-- Chart.js for Category Breakdown -->
<h3 class="my-4">Category Wise Expenses</h3>
<canvas id="categoryChart" width="400" height="200"></canvas>

<!-- Chart.js for Daywise Expenses -->
<h3 class="my-4">Daywise Expenses</h3>
<canvas id="dayChart" width="400" height="200"></canvas>

<script>
    // Category Chart Data
    var categoryLabels = @json($chartCategoryLabels);
    var categoryData = @json($chartCategoryData);

    var ctx = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Expenses by Category',
                data: categoryData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Day Chart Data
    var dayLabels = @json($chartDayLabels);
    var dayData = @json($chartDayData);

    var ctx2 = document.getElementById('dayChart').getContext('2d');
    var dayChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: dayLabels,
            datasets: [{
                label: 'Expenses by Day',
                data: dayData,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
