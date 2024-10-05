<?php

namespace App\Http\Controllers;

use App\DataTables\AccountDataTable;
use App\Models\Account;
use App\Models\Currency;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(AccountDataTable $dataTable)
    {
        return $dataTable->render('accounts.index');
    }

    public function create()
    {
        $currencies = Currency::all();
        return view('accounts.create', compact('currencies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'nullable|string|max:255',
            'currency_code' => 'nullable|string|max:3',
            'is_active' => 'boolean',
        ]);

        Account::create($request->all());

        return redirect()->route('accounts.index')->with('success', 'Account created successfully.');
    }

    public function show($id)
    {
        $account = Account::findOrFail($id);
        return view('accounts.show', compact('account'));
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $currencies = Currency::all();
        return view('accounts.edit', compact('account', 'currencies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'nullable|string|max:255',
            'currency_code' => 'nullable|string|max:3',
            'is_active' => 'boolean',
        ]);

        $account = Account::findOrFail($id);
        $account->update($request->all());

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return redirect()->route('accounts.index')->with('success', 'Account deleted successfully.');
    }
}
