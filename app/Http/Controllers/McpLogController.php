<?php

namespace App\Http\Controllers;

use App\Models\McpLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class McpLogController extends Controller
{
    public function index(): View
    {
        $logs = McpLog::query()
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(25);

        return view('pages.mcp-logs.index', compact('logs'));
    }
}
