<?php

namespace App\Http\Controllers;
use League\Csv\Writer;
use App\Models\Expense;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function exportCsv()
{
    // Fetch the expenses for the authenticated user
    $expenses = Expense::where('user_id', auth()->id())->get();

    $csvData = [];
    foreach ($expenses as $expense) {
        $csvData[] = [
            'Description' => $expense->description,
            'Amount' => $expense->amount,
            'Category' => $expense->category,
            'Date' => $expense->date,
        ];
    }

    $csvFileName = 'expenses_' . now()->format('Y_m_d_H_i_s') . '.csv';

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$csvFileName",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    ];

    // Open the PHP output stream
    $handle = fopen('php://output', 'w');
    
    // Write the CSV header
    fputcsv($handle, ['Description', 'Amount', 'Category', 'Date']);
    
    // Write the data rows
    foreach ($csvData as $row) {
        fputcsv($handle, $row);
    }

    // Return the streamed response and close the handle inside the callback
    return response()->stream(function () use ($handle) {
        fclose($handle); // Close the handle here
    }, 200, $headers);
}

}
