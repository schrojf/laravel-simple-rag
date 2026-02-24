<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravel\Passport\Client;

class TokenController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $tokens = $user->tokens()
            ->where('revoked', false)
            ->orderByDesc('created_at')
            ->get();

        $hasPersonalAccessClient = Client::where('revoked', false)
            ->get()
            ->contains(fn (Client $client) => $client->hasGrantType('personal_access'));

        return view('pages.settings.tokens', compact('tokens', 'hasPersonalAccessClient'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $token = $request->user()->createToken($validated['name']);

        return redirect()->route('settings.tokens')
            ->with('new_token', $token->accessToken);
    }

    public function destroy(string $tokenId): RedirectResponse
    {
        $token = auth()->user()->tokens()->findOrFail($tokenId);
        $token->revoke();

        return redirect()->route('settings.tokens')
            ->with('success', 'Token revoked.');
    }
}
