<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\McpLog;
use App\Models\Response as ResponseModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        $userId = Auth::id();

        $entryCount = Entry::where('user_id', $userId)->count();
        $responseCount = ResponseModel::where('user_id', $userId)->count();
        $mcpLogCount = McpLog::where('user_id', $userId)->count();

        $recentEntries = Entry::where('user_id', $userId)
            ->with('type')
            ->latest()
            ->limit(5)
            ->get();

        $recentMcpLogs = McpLog::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'entryCount',
            'responseCount',
            'mcpLogCount',
            'recentEntries',
            'recentMcpLogs',
        ));
    }
}
