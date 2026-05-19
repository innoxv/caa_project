<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Flight::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('nationality', 'like', "%{$search}%")
                  ->orWhere('license_category', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && in_array($request->status, ['active', 'pending', 'expired'])) {
            $query->where('status', $request->status);
        }

        $flights = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('flight.index', compact('flights'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('flight.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:255',
            'address' => 'nullable|string',
            'application_type' => 'required|string|max:255',
            'license_category' => 'required|string|max:255',
            'total_hours' => 'nullable|integer|min:0',
            'medical_cert' => 'required|string|max:255',
        ]);

        $validated['status'] = 'pending';

        if (auth()->check() && auth()->user()->role === 'admin') {
            $validated['status'] = 'active';
            $validated['issue_date'] = now()->toDateString();
            Flight::create($validated);
            return redirect()->route('admin.flight')->with('success', 'License issued successfully.');
        }

        Flight::create($validated);

        return redirect()->route('success')->with('message', 'Personnel license application submitted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flight $flight)
    {
        return view('flight.create', compact('flight'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flight $flight)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'nationality' => 'required|string|max:255',
            'address' => 'nullable|string',
            'application_type' => 'required|string|max:255',
            'license_category' => 'required|string|max:255',
            'total_hours' => 'nullable|integer|min:0',
            'medical_cert' => 'required|string|max:255',
            'status' => 'required|in:active,pending,expired',
            'issue_date' => 'nullable|date',
        ]);

        if ($validated['status'] === 'active' && $flight->status === 'pending' && empty($validated['issue_date'])) {
            $validated['issue_date'] = now()->toDateString();
        }

        $flight->update($validated);

        return redirect()->route('admin.flight')->with('success', 'License updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flight $flight)
    {
        $flight->delete();

        return redirect()->route('admin.flight')->with('success', 'License record deleted successfully.');
    }
}
