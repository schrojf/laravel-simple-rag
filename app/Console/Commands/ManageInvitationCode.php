<?php

namespace App\Console\Commands;

use App\Models\InvitationCode;
use Illuminate\Console\Command;

class ManageInvitationCode extends Command
{
    protected $signature = 'invitation:manage
                            {action : The action to perform (create, list, deactivate)}
                            {--code= : The invitation code (for deactivate action)}
                            {--description= : Optional description for the invitation code}
                            {--count=1 : Number of codes to generate (for create action)}';

    protected $description = 'Create, list and deactivate invitation codes';

    public function handle(): int
    {
        return match ($this->argument('action')) {
            'create' => $this->runCreate(),
            'list' => $this->runList(),
            'deactivate' => $this->runDeactivate(),
            default => $this->handleUnknownAction(),
        };
    }

    private function runCreate(): int
    {
        $count = (int) $this->option('count');

        if ($count < 1 || $count > 100) {
            $this->error('Count must be between 1 and 100.');

            return self::FAILURE;
        }

        $description = $this->option('description');

        $this->line("Generating {$count} invitation code(s)...");
        $this->newLine();

        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            $code = $this->generateCode();

            InvitationCode::create([
                'code' => $code,
                'description' => $description,
            ]);

            $codes[] = $code;
        }

        $this->line('<fg=green>✓</> Successfully created invitation code(s):');

        foreach ($codes as $code) {
            $this->line("  • {$code}");
        }

        return self::SUCCESS;
    }

    private function runList(): int
    {
        $codes = InvitationCode::query()->latest()->get();

        if ($codes->isEmpty()) {
            $this->info('No invitation codes found.');

            return self::SUCCESS;
        }

        $rows = $codes->map(fn (InvitationCode $code) => [
            $code->id,
            $code->code,
            $code->active ? '✓' : '✗',
            $code->used_at?->format('Y-m-d H:i') ?? '-',
            $code->used_by ?? '-',
            $code->description ? mb_strimwidth($code->description, 0, 30, '…') : '-',
            $code->created_at->format('Y-m-d H:i'),
        ])->toArray();

        $this->table(
            ['ID', 'Code', 'Active', 'Used At', 'Used By', 'Description', 'Created'],
            $rows
        );

        $total = $codes->count();
        $activeUnused = $codes->filter(fn (InvitationCode $c) => $c->active && $c->used_at === null)->count();
        $used = $codes->filter(fn (InvitationCode $c) => $c->used_at !== null)->count();

        $this->newLine();
        $this->line("Total: {$total} | Active & Unused: {$activeUnused} | Used: {$used}");

        return self::SUCCESS;
    }

    private function runDeactivate(): int
    {
        $code = $this->option('code');

        if (! $code) {
            $this->error('The --code option is required for the deactivate action.');

            return self::FAILURE;
        }

        $invitation = InvitationCode::where('code', $code)->first();

        if (! $invitation) {
            $this->error("Invitation code '{$code}' not found.");

            return self::FAILURE;
        }

        if (! $invitation->active) {
            $this->warn("Invitation code '{$code}' is already deactivated.");

            return self::SUCCESS;
        }

        if ($invitation->used_at !== null) {
            $this->warn('Warning: This code has already been used.');
        }

        $invitation->active = false;
        $invitation->save();

        $this->line("<fg=green>✓</> Successfully deactivated invitation code: {$code}");

        return self::SUCCESS;
    }

    private function handleUnknownAction(): int
    {
        $action = $this->argument('action');
        $this->error("Unknown action '{$action}'. Valid actions: create, list, deactivate.");

        return self::FAILURE;
    }

    private function generateCode(): string
    {
        do {
            $code = $this->generateSegment().'-'.$this->generateSegment().'-'.$this->generateSegment();
        } while (InvitationCode::where('code', $code)->exists());

        return $code;
    }

    private function generateSegment(): string
    {
        $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $segment = '';

        for ($i = 0; $i < 3; $i++) {
            $segment .= $charset[random_int(0, strlen($charset) - 1)];
        }

        return $segment;
    }
}
