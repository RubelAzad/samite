<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Expense;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('createdBy')
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->from, fn($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('date', '<=', $request->to));

        $expenses   = $query->latest()->paginate(20)->withQueryString();
        $categories = Expense::categories();

        return view('admin.expenses.index', compact('expenses', 'categories'));
    }

    public function create()
    {
        $categories = Expense::categories();
        return view('admin.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|in:' . implode(',', array_keys(Expense::categories())),
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'description' => 'nullable|string',
            'attachment'  => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('expenses', 'public');
            }

            $expense = Expense::create([
                'title'       => $request->title,
                'category'    => $request->category,
                'amount'      => $request->amount,
                'date'        => $request->date,
                'description' => $request->description,
                'attachment'  => $attachmentPath,
                'status'      => 'approved',
                'created_by'  => auth()->id(),
            ]);

            LedgerEntry::recordExpense($expense);

            AuditLog::record('create', 'expense', $expense->id, null, $expense->toArray(), "Created expense: {$expense->title}");
        });

        return redirect()->route('admin.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load('createdBy');
        return view('admin.expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $categories = Expense::categories();
        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|in:' . implode(',', array_keys(Expense::categories())),
            'amount'      => 'required|numeric|min:0.01',
            'date'        => 'required|date',
            'description' => 'nullable|string',
            'attachment'  => 'nullable|image|max:2048',
        ]);

        $old = $expense->toArray();

        if ($request->hasFile('attachment')) {
            if ($expense->attachment) {
                Storage::disk('public')->delete($expense->attachment);
            }
            $expense->attachment = $request->file('attachment')->store('expenses', 'public');
        }

        $expense->update([
            'title'       => $request->title,
            'category'    => $request->category,
            'amount'      => $request->amount,
            'date'        => $request->date,
            'description' => $request->description,
            'attachment'  => $expense->attachment,
        ]);

        AuditLog::record('update', 'expense', $expense->id, $old, $expense->toArray(), "Updated expense: {$expense->title}");

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        AuditLog::record('delete', 'expense', $expense->id, $expense->toArray(), null, "Deleted expense: {$expense->title}");

        if ($expense->attachment) {
            Storage::disk('public')->delete($expense->attachment);
        }

        $expense->delete();

        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted.');
    }
}
