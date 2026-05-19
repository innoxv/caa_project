<?php

namespace App\Http\Controllers;

use App\Models\Aircraft;
use Illuminate\Http\Request;

class AircraftController extends Controller
{
    /**
     * Display a listing of the resource (Admin View).
     */
    public function index(Request $request)
    {
        $query = Aircraft::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('registration_mark', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && in_array($request->status, ['active', 'pending', 'suspended'])) {
            $query->where('status', $request->status);
        }

        $aircrafts = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('aircraft.index', compact('aircrafts'));
    }

    /**
     * Show the form for creating a new resource (Public and Admin View).
     */
    public function create()
    {
        return view('aircraft.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'registration_mark' => 'nullable|string|max:10',
            'owner_name' => 'required|string|max:255',
            'owner_address' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_phone' => 'required|string|max:50',
        ]);

        // Default mark generation or uppercase formatting
        if (!empty($validated['registration_mark'])) {
            $validated['registration_mark'] = strtoupper($validated['registration_mark']);
        } else {
            // Assign a random 3-letter mark if left blank
            $validated['registration_mark'] = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        }

        // Default status is pending
        $validated['status'] = 'pending';

        // Check if admin is creating
        if (auth()->check() && auth()->user()->role === 'admin') {
            $validated['status'] = 'active'; // Admins can create active registrations
            $validated['issue_date'] = now()->toDateString();
            Aircraft::create($validated);
            return redirect()->route('admin.aircraft')->with('success', 'Aircraft registered successfully.');
        }

        Aircraft::create($validated);

        return redirect()->route('success')->with('message', 'Aircraft registration application submitted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Aircraft $aircraft)
    {
        return view('aircraft.create', compact('aircraft'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Aircraft $aircraft)
    {
        $validated = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'registration_mark' => 'required|string|max:10',
            'owner_name' => 'required|string|max:255',
            'owner_address' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_phone' => 'required|string|max:50',
            'status' => 'required|in:active,pending,suspended',
            'issue_date' => 'nullable|date',
        ]);

        $validated['registration_mark'] = strtoupper($validated['registration_mark']);

        // Automatically set issue date if status goes from pending to active
        if ($validated['status'] === 'active' && $aircraft->status === 'pending' && empty($validated['issue_date'])) {
            $validated['issue_date'] = now()->toDateString();
        }

        $aircraft->update($validated);

        return redirect()->route('admin.aircraft')->with('success', 'Aircraft registration updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Aircraft $aircraft)
    {
        $aircraft->delete();

        return redirect()->route('admin.aircraft')->with('success', 'Aircraft record deleted successfully.');
    }
}
