<?php

namespace App\Http\Controllers;

use App\Models\GekoLahan;
use Illuminate\Http\Request;

class MergeController extends Controller
{
    /**
     * Dashboard utama - menampilkan statistik dan filter
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $filters = [
            'village' => $request->get('village'),
            'kecamatan' => $request->get('kecamatan'),
            'city' => $request->get('city'),
            'province' => $request->get('province'),
            'source' => $request->get('source'), // geko, bhl, merged, bhl_only, geko_only
        ];

        // Build query with filters
        $query = GekoLahan::query();

        // Apply location filters
        if ($filters['village']) {
            $query->byVillage($filters['village']);
        }
        if ($filters['kecamatan']) {
            $query->byKecamatan($filters['kecamatan']);
        }
        if ($filters['city']) {
            $query->byCity($filters['city']);
        }
        if ($filters['province']) {
            $query->byProvince($filters['province']);
        }

        // Apply source filter
        switch ($filters['source']) {
            case 'geko':
                $query->fromGeko();
                break;
            case 'bhl':
                $query->fromBhl();
                break;
            case 'merged':
                $query->merged();
                break;
            case 'bhl_only':
                $query->bhlOnly();
                break;
            case 'geko_only':
                $query->gekoOnly();
                break;
        }

        // Get filtered results dengan pagination
        $lahan = $query->orderBy('id', 'desc')->paginate(50)->withQueryString();

        // Get statistics (untuk filtered data)
        $filteredStats = [
            'total_lahan' => $query->count(),
            'total_petani' => (clone $query)->distinct('farmer_no')->count('farmer_no'),
        ];

        // Get global statistics
        $globalStats = GekoLahan::getStatistics();

        // Get unique locations for filter dropdowns
        $locations = GekoLahan::getUniqueLocations();

        return view('merge.index', compact('lahan', 'filters', 'filteredStats', 'globalStats', 'locations'));
    }

    /**
     * API endpoint untuk statistik
     */
    public function statistics(Request $request)
    {
        $stats = GekoLahan::getStatistics();
        return response()->json($stats);
    }

    /**
     * API endpoint untuk data lahan dengan filter
     */
    public function apiLahan(Request $request)
    {
        $query = GekoLahan::query();

        // Apply filters
        if ($request->has('village')) {
            $query->byVillage($request->village);
        }
        if ($request->has('kecamatan')) {
            $query->byKecamatan($request->kecamatan);
        }
        if ($request->has('city')) {
            $query->byCity($request->city);
        }
        if ($request->has('source')) {
            switch ($request->source) {
                case 'geko':
                    $query->fromGeko();
                    break;
                case 'bhl':
                    $query->fromBhl();
                    break;
                case 'merged':
                    $query->merged();
                    break;
                case 'bhl_only':
                    $query->bhlOnly();
                    break;
                case 'geko_only':
                    $query->gekoOnly();
                    break;
            }
        }

        $lahan = $query->select([
            'id',
            'lahan_no',
            'village',
            'kecamatan',
            'city',
            'province',
            'land_area',
            'farmer_no',
            'data_source',
            'exists_in_geko',
            'exists_in_bhl'
        ])->paginate($request->get('per_page', 50));

        return response()->json($lahan);
    }
}
