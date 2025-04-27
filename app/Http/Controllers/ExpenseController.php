<?php

namespace App\Http\Controllers;

use League\Csv\Writer;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve expenses', 'message' => $e->getMessage()], 500);
        }
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
        // Validate request data
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        try {
            // Create the new expense
            Expense::create([
                'user_id' => auth()->id(),
                'description' => $validated['description'],
                'amount' => $validated['amount'],
                'category' => $validated['category'],
                'date' => $validated['date'],
            ]);

            return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add expense', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        if (!$expense) {
            return response()->json(['error' => 'Expense not found'], 404);
        }

        return view('expenses.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
        ]);

        try {
            // Update the expense
            $expense->update([
                'description' => $request->description,
                'amount' => $request->amount,
                'category' => $request->category,
                'date' => $request->date,
            ]);

            return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update expense', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        try {
            // Delete the expense
            $expense->delete();

            return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete expense', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get daywise expenses.
     */
    public function getDaywiseExpenses($date)
    {
        try {
            // Fetch all expenses for the selected day
            $expenses = Expense::where('user_id', auth()->id())
                                ->whereDate('date', $date) // Filter by the specific date
                                ->get();

            // Calculate total expenses for the selected day
            $totalAmount = $expenses->sum('amount');

            return response()->json([
                'totalAmount' => $totalAmount,
                'expenses' => $expenses,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch daywise expenses', 'message' => $e->getMessage()], 500);
        }
    }
}
