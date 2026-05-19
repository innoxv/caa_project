<?php

namespace App\Http\Controllers;

use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\Medical;
use App\Models\Mro;
use App\Models\Organization;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Gather Metrics
        $totalAircraft = Aircraft::count();
        $approvedMros = Mro::where('status', 'approved')->count();
        $activePersonnel = Flight::where('status', 'active')->count();
        $medicalsPending = Medical::where('status', 'pending')->count();
        $organizations = Organization::count();

        // 2. Fetch Pending Approvals from all 5 services
        $aircraftPending = Aircraft::where('status', 'pending')->get()->map(function ($item) {
            return [
                'id' => 'APP-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'type' => 'Aircraft Registration',
                'badge_class' => 'bg-blue-100 text-blue-800',
                'applicant' => $item->owner_name,
                'date_raw' => $item->created_at,
                'date' => $item->created_at->format('d M Y'),
                'route' => route('admin.aircraft.edit', $item->id),
            ];
        });

        $medicalsPendingList = Medical::where('status', 'pending')->get()->map(function ($item) {
            return [
                'id' => 'MED-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'type' => $item->medical_class . ' Medical',
                'badge_class' => 'bg-green-100 text-green-800',
                'applicant' => $item->full_name,
                'date_raw' => $item->created_at,
                'date' => $item->created_at->format('d M Y'),
                'route' => route('admin.medical.edit', $item->id),
            ];
        });

        $mrosPending = Mro::where('status', 'pending')->get()->map(function ($item) {
            return [
                'id' => 'MRO-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'type' => 'MRO ' . $item->application_type,
                'badge_class' => 'bg-purple-100 text-purple-800',
                'applicant' => $item->name,
                'date_raw' => $item->created_at,
                'date' => $item->created_at->format('d M Y'),
                'route' => route('admin.mro.edit', $item->id),
            ];
        });

        $flightsPending = Flight::where('status', 'pending')->get()->map(function ($item) {
            return [
                'id' => 'PL-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'type' => $item->license_category,
                'badge_class' => 'bg-amber-100 text-amber-800',
                'applicant' => $item->first_name . ' ' . $item->last_name,
                'date_raw' => $item->created_at,
                'date' => $item->created_at->format('d M Y'),
                'route' => route('admin.flight.edit', $item->id),
            ];
        });

        $orgsPending = Organization::where('status', 'pending')->get()->map(function ($item) {
            return [
                'id' => 'ORG-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'type' => 'Org ' . $item->type,
                'badge_class' => 'bg-rose-100 text-rose-800',
                'applicant' => $item->name,
                'date_raw' => $item->created_at,
                'date' => $item->created_at->format('d M Y'),
                'route' => route('admin.organization.edit', $item->id),
            ];
        });

        $pendingApprovals = collect()
            ->merge($aircraftPending)
            ->merge($medicalsPendingList)
            ->merge($mrosPending)
            ->merge($flightsPending)
            ->merge($orgsPending)
            ->sortByDesc('date_raw')
            ->take(10);

        // 3. Submissions per Month for the last 6 months
        $trends = [];
        $maxCount = 0;
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $start = $monthDate->copy()->startOfMonth();
            $end = $monthDate->copy()->endOfMonth();

            $count = Aircraft::whereBetween('created_at', [$start, $end])->count()
                   + Flight::whereBetween('created_at', [$start, $end])->count()
                   + Medical::whereBetween('created_at', [$start, $end])->count()
                   + Mro::whereBetween('created_at', [$start, $end])->count()
                   + Organization::whereBetween('created_at', [$start, $end])->count();

            if ($count > $maxCount) {
                $maxCount = $count;
            }

            $trends[] = [
                'label' => $monthDate->format('M'),
                'count' => $count,
            ];
        }

        // Add percentage height for trend bars
        foreach ($trends as &$trend) {
            $trend['percentage'] = $maxCount > 0 ? round(($trend['count'] / $maxCount) * 100) : 0;
            // Cap minimum height at 8% so 0 or small values still show a tiny indicator
            if ($trend['percentage'] < 8) {
                $trend['percentage'] = 8;
            }
        }

        return view('dashboard', compact(
            'totalAircraft',
            'approvedMros',
            'activePersonnel',
            'medicalsPending',
            'organizations',
            'pendingApprovals',
            'trends'
        ));
    }
}
