<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInvitationCodeRequest;
use App\Http\Requests\Admin\UpdateInvitationCodeRequest;
use App\Models\InvitationCode;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class InvitationCodeController extends Controller
{
    public function index(): View
    {
        $invitationCodes = InvitationCode::query()
            ->latest()
            ->paginate(15);

        return view('pages.admin.invitation-codes.index', compact('invitationCodes'));
    }

    public function create(): View
    {
        return view('pages.admin.invitation-codes.create');
    }

    public function store(StoreInvitationCodeRequest $request): RedirectResponse
    {
        $code = filled($request->input('code'))
            ? strtoupper($request->input('code'))
            : InvitationCode::generateCode();

        InvitationCode::create([
            'code' => $code,
            'description' => $request->input('description'),
            'active' => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.invitation-codes.index')
            ->with('success', "Invitation code {$code} created successfully.");
    }

    public function show(InvitationCode $invitationCode): RedirectResponse
    {
        return redirect()->route('admin.invitation-codes.edit', $invitationCode);
    }

    public function edit(InvitationCode $invitationCode): View
    {
        return view('pages.admin.invitation-codes.edit', compact('invitationCode'));
    }

    public function update(UpdateInvitationCodeRequest $request, InvitationCode $invitationCode): RedirectResponse
    {
        $invitationCode->update([
            'description' => $request->input('description'),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.invitation-codes.index')
            ->with('success', "Invitation code {$invitationCode->code} updated successfully.");
    }

    public function destroy(InvitationCode $invitationCode): RedirectResponse
    {
        $code = $invitationCode->code;
        $invitationCode->delete();

        return redirect()->route('admin.invitation-codes.index')
            ->with('success', "Invitation code {$code} deleted successfully.");
    }
}
