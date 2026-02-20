<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Contracts\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->latest()->paginate(15);

        return view('pages.admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $usedInvitationCode = InvitationCode::query()->where('used_by', $user->id)->first();

        return view('pages.admin.users.show', compact('user', 'usedInvitationCode'));
    }
}
