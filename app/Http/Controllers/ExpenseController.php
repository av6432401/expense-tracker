<?php

namespace App\Http\Controllers;
use League\Csv\Writer;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Fetch all expenses for the authenticated user
    $expenses = Expense::where('user_id', auth()->id())->get();
    
    // Get the total expenses
    $totalExpense = $expenses->sum('amount');
    
    // Group expenses by category
    $categorySummary = $expenses->groupBy('category')->map(function($category) {
        return $category->sum('amount');
    });
    
    // Group expenses by day (formatted date)
    $daywiseSummary = $expenses->groupBy(function($expense) {
        return \Carbon\Carbon::parse($expense->date)->format('Y-m-d'); // Date in Y-m-d format
    })->map(function($day) {
        return $day->sum('amount');
    });
    
    // Prepare data for Chart.js (category-wise)
    $chartCategoryLabels = $categorySummary->keys();
    $chartCategoryData = $categorySummary->values();
    
    // Prepare data for Chart.js (day-wise)
    $chartDayLabels = $daywiseSummary->keys();
    $chartDayData = $daywiseSummary->values();
    
    // Get the unique days with expenses
    $daysWithExpenses = $daywiseSummary->keys()->toArray();
    
    return view('expenses.index', compact(
        'expenses', 
        'totalExpense', 
        'categorySummary', 
        'daywiseSummary', 
        'daysWithExpenses', 
        'chartCategoryLabels', 
        'chartCategoryData', 
        'chartDayLabels', 
        'chartDayData'
    ));
}

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => auth()->id(),
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'date' => $request->date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        $expense->update([
            'description' => $request->description,
            'amount' => $request->amount,
            'category' => $request->category,
            'date' => $request->date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
    public function getDaywiseExpenses($date)
{
    // Fetch all expenses for the selected day
    $expenses = Expense::where('user_id', auth()->id())
                        ->whereDate('date', $date) // Filter by the specific date
                        ->get();

    // Calculate total expenses for the selected day
    $totalAmount = $expenses->sum('amount');

    return response()->json([
        'totalAmount' => $totalAmount,
        'expenses' => $expenses,
    ]);
}

    


}
