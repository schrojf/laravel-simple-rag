<?php

namespace App\Actions\Fortify;

use App\Actions\SeedDefaultUserContent;
use App\Models\InvitationCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        return DB::transaction(function () use ($input) {
            Validator::make($input, [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique(User::class),
                ],
                'password' => $this->passwordRules(),
            ])->validate();

            if (config('app.require_invitation')) {
                Validator::make($input, [
                    'invitation_code' => ['required', 'string', 'size:11'],
                ])->validate();

                $code = strtoupper($input['invitation_code']);

                $invitation = InvitationCode::where('code', $code)
                    ->where('active', true)
                    ->lockForUpdate()
                    ->first();

                if (! $invitation) {
                    throw ValidationException::withMessages([
                        'invitation_code' => ['Invitation code not found.'],
                    ]);
                }

                if ($invitation->used_at !== null) {
                    throw ValidationException::withMessages([
                        'invitation_code' => ['Invitation code already used.'],
                    ]);
                }
            }

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            if (config('app.require_invitation') && isset($invitation)) {
                $invitation->used_at = now();
                $invitation->used_by = $user->id;
                $invitation->save();
            }

            if (config('app.seed_default_content')) {
                app(SeedDefaultUserContent::class)->seed($user);
            }

            return $user;
        });
    }
}
