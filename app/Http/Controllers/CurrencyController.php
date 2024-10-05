<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\DataTables\CurrencyDataTable;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CurrencyDataTable $dataTable)
    {
        return $dataTable->render('currencies.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency_code' => 'required|string|max:3|unique:currencies,currency_code',
            'currency_name' => 'required|string',
            'exchange_rate' => 'required|numeric|min:0',
        ]);

        Currency::create($validated);

        return redirect()->route('currencies.index')->with('success', 'Currency added successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show(Currency $currency)
    {
        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return view('currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'currency_code' => 'required|string|max:3|unique:currencies,currency_code,' . $currency->id,
            'currency_name' => 'required|string',
            'exchange_rate' => 'required|numeric|min:0',
        ]);

        $currency->update($validated);

        return redirect()->route('currencies.index')->with('success', 'Currency updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }
}
