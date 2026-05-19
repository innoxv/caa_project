<?php

namespace App\Http\Controllers;

use App\Models\Medical;
use Illuminate\Http\Request;

class MedicalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Medical::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%")
                  ->orWhere('medical_class', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && in_array($request->status, ['approved', 'pending', 'rejected'])) {
            $query->where('status', $request->status);
        }

        $medicals = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('medical.index', compact('medicals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medical.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'medical_class' => 'required|string|max:255',
            'dob' => 'required|date',
        ]);

        $validated['status'] = 'pending';

        if (auth()->check() && auth()->user()->role === 'admin') {
            $validated['status'] = 'approved';
            $validated['issue_date'] = now()->toDateString();
            Medical::create($validated);
            return redirect()->route('admin.medical')->with('success', 'Medical certificate issued successfully.');
        }

        Medical::create($validated);

        return redirect()->route('success')->with('message', 'Medical certificate application submitted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medical $medical)
    {
        return view('medical.create', compact('medical'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medical $medical)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'medical_class' => 'required|string|max:255',
            'dob' => 'required|date',
            'status' => 'required|in:approved,pending,rejected',
            'issue_date' => 'nullable|date',
        ]);

        if ($validated['status'] === 'approved' && $medical->status === 'pending' && empty($validated['issue_date'])) {
            $validated['issue_date'] = now()->toDateString();
        }

        $medical->update($validated);

        return redirect()->route('admin.medical')->with('success', 'Medical certificate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medical $medical)
    {
        $medical->delete();

        return redirect()->route('admin.medical')->with('success', 'Medical record deleted successfully.');
    }
}
