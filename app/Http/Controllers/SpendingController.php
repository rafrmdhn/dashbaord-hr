<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Spending;
use Illuminate\Http\Request;
use App\Exports\SpendingExport;
use Maatwebsite\Excel\Facades\Excel;

class SpendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('spending.main', [
            'title' => 'Pengeluaran',
            'search' => 'spendings',
            'tables' => Spending::latest()->filter(request(['search', 'name', 'bulan', 'tahun']))->paginate(10)->withQueryString(),
            'staffs' => Staff::all(),
            'export' => 'exportSpending'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'staff_id' => 'required',
            'requirement' => 'required',
            'budget' => 'required',
            'date' => 'required',
            'proof' => 'image|file|max:1024', // 1MB Max
        ]);

        if($request->file('proof')) {
            $validatedData['proof'] = $request->file('proof')->store('images/spends');
        }

        Spending::create($validatedData);

        return redirect('/spendings')->with('success', 'Data has been added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Spending $spending)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Spending $spending)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Spending $spending)
    {
        $validatedData = $request->validate([
            'status' => 'required'
        ]);

        Spending::where('id', $spending->id)
                ->update($validatedData);

        return redirect('/spendings')->with('success', 'Data has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Spending $spending)
    {
        Spending::destroy($spending->id);

        return redirect('/spendings')->with('success', 'Data has been deleted!');
    }

    public function export()
    {
        return Excel::download(new SpendingExport, 'spending.xlsx');
    }
}
