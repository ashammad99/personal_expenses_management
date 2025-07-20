<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $month = now()->format('Y-m');

        $income = $user->incomes()->where('month', $month)->sum('income');
        $expenses = $user->expenses()->where('month', $month)->sum('amount');

        $recentExpenses = $user->expenses()
            ->where('month', $month)
            ->latest()
            ->take(5)
            ->with('category')
            ->get();

        $categories = $user->expenses()
            ->where('month', $month)
            ->with('category')
            ->get()
            ->groupBy(fn($e) => $e->category->name ?? 'Other');

        $chartLabels = $categories->keys();
        $chartData = $categories->map(fn($items) => $items->sum('amount'))->values();

        return view('dashboard', compact('income', 'expenses', 'recentExpenses', 'chartLabels', 'chartData'));
    }

}
