<?php

namespace App\Http\Controllers;

use App\Models\Mro;
use Illuminate\Http\Request;

class MroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mro::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('certificate_no', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('accountable_manager', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && in_array($request->status, ['approved', 'pending', 'expired'])) {
            $query->where('status', $request->status);
        }

        $mros = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('mro.index', compact('mros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mro.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'application_type' => 'required|string|max:255',
            'ratings' => 'nullable|array',
            'accountable_manager' => 'required|string|max:255',
            'quality_manager' => 'required|string|max:255',
        ]);

        $validated['status'] = 'pending';
        $validated['ratings'] = $request->input('ratings', []);

        if (auth()->check() && auth()->user()->role === 'admin') {
            $validated['status'] = 'approved';
            $validated['certificate_no'] = 'MRO/UG/' . str_pad(Mro::count() + 1, 3, '0', STR_PAD_LEFT);
            $validated['expiry_date'] = now()->addYear()->toDateString();
            Mro::create($validated);
            return redirect()->route('admin.mro')->with('success', 'MRO Facility registered successfully.');
        }

        Mro::create($validated);

        return redirect()->route('success')->with('message', 'MRO Facility approval application submitted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mro $mro)
    {
        return view('mro.create', compact('mro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mro $mro)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'application_type' => 'required|string|max:255',
            'ratings' => 'nullable|array',
            'accountable_manager' => 'required|string|max:255',
            'quality_manager' => 'required|string|max:255',
            'status' => 'required|in:approved,pending,expired',
            'certificate_no' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
        ]);

        $validated['ratings'] = $request->input('ratings', []);

        if ($validated['status'] === 'approved' && $mro->status === 'pending') {
            if (empty($validated['certificate_no'])) {
                $validated['certificate_no'] = 'MRO/UG/' . str_pad($mro->id, 3, '0', STR_PAD_LEFT);
            }
            if (empty($validated['expiry_date'])) {
                $validated['expiry_date'] = now()->addYear()->toDateString();
            }
        }

        $mro->update($validated);

        return redirect()->route('admin.mro')->with('success', 'MRO Facility updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mro $mro)
    {
        $mro->delete();

        return redirect()->route('admin.mro')->with('success', 'MRO record deleted successfully.');
    }
}
