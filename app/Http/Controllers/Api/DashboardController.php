<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\FinancialTransaction;
use App\Models\MemberFee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $user = auth()->user();
        
        $data = [
            'user_info' => [
                'name' => $user->name,
                'role' => $user->role,
                'nursery_name' => $user->nursery_name,
            ],
            'my_tasks' => Task::where('assigned_to', $user->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->count(),
            'upcoming_meetings' => Meeting::upcoming()
                ->whereHas('attendees', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count(),
            'overdue_tasks' => Task::overdue()
                ->where('assigned_to', $user->id)
                ->count(),
        ];

        // Información adicional para roles administrativos
        if (in_array($user->role, ['admin', 'president', 'treasurer'])) {
            $data['admin_stats'] = [
                'total_members' => \App\Models\User::active()->count(),
                'pending_transactions' => FinancialTransaction::pending()->count(),
                'overdue_fees' => MemberFee::overdue()->count(),
            ];
        }

        return response()->json($data);
    }

    public function tasksSummary(): JsonResponse
    {
        $summary = [
            'total_tasks' => Task::count(),
            'pending_tasks' => Task::pending()->count(),
            'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
            'completed_tasks' => Task::completed()->count(),
            'overdue_tasks' => Task::overdue()->count(),
            'by_priority' => [
                'urgent' => Task::byPriority('urgent')->whereIn('status', ['pending', 'in_progress'])->count(),
                'high' => Task::byPriority('high')->whereIn('status', ['pending', 'in_progress'])->count(),
                'medium' => Task::byPriority('medium')->whereIn('status', ['pending', 'in_progress'])->count(),
                'low' => Task::byPriority('low')->whereIn('status', ['pending', 'in_progress'])->count(),
            ],
        ];

        return response()->json($summary);
    }

    public function upcomingMeetings(): JsonResponse
    {
        $meetings = Meeting::upcoming()
            ->with(['organizer:id,name', 'attendees.user:id,name'])
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        return response()->json($meetings);
    }

    public function financialSummary(): JsonResponse
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        $summary = [
            'current_year' => [
                'income' => FinancialTransaction::income()
                    ->approved()
                    ->whereYear('transaction_date', $currentYear)
                    ->sum('amount'),
                'expenses' => FinancialTransaction::expense()
                    ->approved()
                    ->whereYear('transaction_date', $currentYear)
                    ->sum('amount'),
            ],
            'current_month' => [
                'income' => FinancialTransaction::income()
                    ->approved()
                    ->whereYear('transaction_date', $currentYear)
                    ->whereMonth('transaction_date', $currentMonth)
                    ->sum('amount'),
                'expenses' => FinancialTransaction::expense()
                    ->approved()
                    ->whereYear('transaction_date', $currentYear)
                    ->whereMonth('transaction_date', $currentMonth)
                    ->sum('amount'),
            ],
            'pending_approvals' => FinancialTransaction::pending()->count(),
            'overdue_fees' => MemberFee::overdue()->count(),
            'paid_fees_this_year' => MemberFee::paid()
                ->whereYear('paid_date', $currentYear)
                ->sum('amount'),
        ];

        $summary['current_year']['balance'] = $summary['current_year']['income'] - $summary['current_year']['expenses'];
        $summary['current_month']['balance'] = $summary['current_month']['income'] - $summary['current_month']['expenses'];

        return response()->json($summary);
    }
}