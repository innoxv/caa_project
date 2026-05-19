<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Organization::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && in_array($request->status, ['active', 'pending', 'inactive'])) {
            $query->where('status', $request->status);
        }

        $organizations = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('organization.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('organization.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $validated['status'] = 'pending';

        if (auth()->check() && auth()->user()->role === 'admin') {
            $validated['status'] = 'active';
            $validated['issue_date'] = now()->toDateString();
            Organization::create($validated);
            return redirect()->route('admin.organization')->with('success', 'Organization registered successfully.');
        }

        Organization::create($validated);

        return redirect()->route('success')->with('message', 'Organization registration application submitted successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organization $organization)
    {
        return view('organization.create', compact('organization'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|in:active,pending,inactive',
            'issue_date' => 'nullable|date',
        ]);

        if ($validated['status'] === 'active' && $organization->status === 'pending' && empty($validated['issue_date'])) {
            $validated['issue_date'] = now()->toDateString();
        }

        $organization->update($validated);

        return redirect()->route('admin.organization')->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();

        return redirect()->route('admin.organization')->with('success', 'Organization record deleted successfully.');
    }
}
