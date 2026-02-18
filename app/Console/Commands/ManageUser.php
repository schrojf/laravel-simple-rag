<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ManageUser extends Command
{
    protected $signature = 'user:manage
                            {action : The action to perform (list, promote, demote)}
                            {--email= : The user email address (for promote/demote actions)}';

    protected $description = 'List users and manage admin privileges';

    public function handle(): int
    {
        return match ($this->argument('action')) {
            'list' => $this->runList(),
            'promote' => $this->runPromote(),
            'demote' => $this->runDemote(),
            default => $this->handleUnknownAction(),
        };
    }

    private function runList(): int
    {
        $users = User::query()->latest()->get();

        if ($users->isEmpty()) {
            $this->info('No users found.');

            return self::SUCCESS;
        }

        $rows = $users->map(fn (User $user) => [
            $user->id,
            $user->name,
            $user->email,
            $user->isAdmin() ? '✓' : '✗',
            $user->created_at->format('Y-m-d'),
        ])->toArray();

        $this->table(['ID', 'Name', 'Email', 'Admin', 'Joined'], $rows);

        $total = $users->count();
        $admins = $users->filter(fn (User $u) => $u->isAdmin())->count();

        $this->newLine();
        $this->line("Total: {$total} | Admins: {$admins}");

        return self::SUCCESS;
    }

    private function runPromote(): int
    {
        $email = $this->option('email');

        if (! $email) {
            $this->error('The --email option is required for the promote action.');

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");

            return self::FAILURE;
        }

        if ($user->isAdmin()) {
            $this->warn("{$user->name} ({$email}) is already an admin.");

            return self::SUCCESS;
        }

        $user->is_admin = true;
        $user->save();

        $this->line("<fg=green>✓</> {$user->name} ({$email}) promoted to admin.");

        return self::SUCCESS;
    }

    private function runDemote(): int
    {
        $email = $this->option('email');

        if (! $email) {
            $this->error('The --email option is required for the demote action.');

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");

            return self::FAILURE;
        }

        if (! $user->isAdmin()) {
            $this->warn("{$user->name} ({$email}) is not an admin.");

            return self::SUCCESS;
        }

        $user->is_admin = false;
        $user->save();

        $this->line("<fg=green>✓</> {$user->name} ({$email}) demoted from admin.");

        return self::SUCCESS;
    }

    private function handleUnknownAction(): int
    {
        $action = $this->argument('action');
        $this->error("Unknown action '{$action}'. Valid actions: list, promote, demote.");

        return self::FAILURE;
    }
}
