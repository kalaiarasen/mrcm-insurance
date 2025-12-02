<?php

namespace App\Http\Controllers;

use App\Models\AgentCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AgentCommissionController extends Controller
{
    /**
     * Display a listing of agent commissions.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AgentCommission::with(['client', 'policyApplication'])
                ->where('agent_id', Auth::id())
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->addColumn('date', function ($commission) {
                    return '<small>' . $commission->created_at->format('d-M-Y') . '</small>';
                })
                ->addColumn('client_name', function ($commission) {
                    return '<strong>' . e($commission->client->name ?? '-') . '</strong>';
                })
                ->addColumn('client_code', function ($commission) {
                    return e($commission->client->client_code ?? '-');
                })
                ->addColumn('policy_ref', function ($commission) {
                    return e($commission->policyApplication->reference_number ?? '-');
                })
                ->addColumn('base_amount', function ($commission) {
                    return 'RM ' . number_format($commission->base_amount, 2);
                })
                ->addColumn('commission_rate', function ($commission) {
                    return $commission->commission_rate . '%';
                })
                ->addColumn('commission_amount', function ($commission) {
                    return '<strong>RM ' . number_format($commission->commission_amount, 2) . '</strong>';
                })
                ->addColumn('status', function ($commission) {
                    $status = $commission->status;
                    $badge = $status === 'active' ? 'success' : 'warning';
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($status) . '</span>';
                })
                ->filterColumn('client_name', function($query, $keyword) {
                    $query->whereHas('client', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('client_code', function($query, $keyword) {
                    $query->whereHas('client', function($q) use ($keyword) {
                        $q->where('client_code', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('policy_ref', function($query, $keyword) {
                    $query->whereHas('policyApplication', function($q) use ($keyword) {
                        $q->where('reference_number', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns(['date', 'client_name', 'commission_amount', 'status'])
                ->make(true);
        }

        // Calculate summary statistics
        $totalPending = AgentCommission::where('agent_id', Auth::id())
            ->whereHas('policyApplication', function($q) {
                $q->where('admin_status', '!=', 'active');
            })
            ->sum('commission_amount');

        $totalActive = AgentCommission::where('agent_id', Auth::id())
            ->whereHas('policyApplication', function($q) {
                $q->where('admin_status', 'active');
            })
            ->sum('commission_amount');

        $totalEarned = AgentCommission::where('agent_id', Auth::id())
            ->sum('commission_amount');

        $totalCommissions = AgentCommission::where('agent_id', Auth::id())->count();

        return view('pages.agent.commissions', compact(
            'totalPending',
            'totalActive',
            'totalEarned',
            'totalCommissions'
        ));
    }
}
