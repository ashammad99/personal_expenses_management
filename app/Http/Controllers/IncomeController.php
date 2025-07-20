<?php

namespace App\Http\Controllers;


use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IncomeController extends Controller
{
    public function showExpensesForMonth(Income $income)
    {
        $month = $income->month;

        $expenses = Auth::user()
            ->expenses()
            ->where('month', $month)
            ->with('category')
            ->get();

        $totalExpenses = $expenses->sum('amount');

        return view('incomes.expenses', compact('expenses', 'income', 'totalExpenses'));
    }
    public function index()
    {
//        $incomes = Income::query()->where('user_id', '=', Auth::user()->id)->paginate();
        $incomes = Auth::user()->incomes()->paginate();

        return view('incomes.list', compact('incomes'));
    }

    public function show()
    {
        $income = Income::where('user_id', Auth::id())
            ->where('month', now()->format('Y-m'))
            ->first();

        return view('incomes.show', compact('income'));
    }

    public function create()
    {
        return view('incomes.create');
    }

    public function store(Request $request)
    {
        $currentMonth = now()->format('Y-m');
        $existingIncome = Income::where('user_id', Auth::id())
            ->where('month', $currentMonth)
            ->first();

        if ($existingIncome) {
            return redirect()->back()->with('error', 'You have already added your income for this month.');
        }
        $request->validate([
            'income' => [
                'required',
                'numeric',
                'min:0',
            ],
        ], [
            'income.unique' => 'You have already entered your income for this month.',
        ]);
        $income = Income::query()->create([
            'income' => $request->input('income'),
            'user_id' => Auth::id(),
            'month' => now()->format('Y-m')
        ]);

        return redirect()->route('incomes.show')->with('message', 'Income added!');
    }

    public function edit(Income $income)
    {
        return view('incomes.edit',compact('income'));
    }

    public function update(Request $request,Income $income)
    {
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'income' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);
        $result = $income->update([
            'income' => $request->input('income'),
        ]);
        return redirect()->route('incomes.show')->with('message', 'Income updated successfully!');
    }

    public function destroy()
    {

    }

}
