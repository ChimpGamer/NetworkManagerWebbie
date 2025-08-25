<?php

namespace App\Console\Commands;

use App\Models\OAuthInvite;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ManageOAuthInvites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oauth:invites 
                            {action : Action to perform (create, list, revoke, cleanup)}
                            {--email= : Email address for the invite (required for create)}
                            {--expires= : Expiration time in hours (default: 168 = 7 days)}
                            {--single-use : Make the invite single-use only}
                            {--max-uses= : Maximum number of uses (default: 1)}
                            {--code= : Specific invite code (for revoke action)}
                            {--expired : Include expired invites in list}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage OAuth invitation codes for restricted registration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'create':
                return $this->createInvite();
            case 'list':
                return $this->listInvites();
            case 'revoke':
                return $this->revokeInvite();
            case 'cleanup':
                return $this->cleanupInvites();
            default:
                $this->error('Invalid action. Available actions: create, list, revoke, cleanup');
                return 1;
        }
    }

    /**
     * Create a new OAuth invite
     */
    private function createInvite()
    {
        $email = $this->option('email');
        if (!$email) {
            $email = $this->ask('Email address for the invite (optional)');
        }

        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address format.');
            return 1;
        }

        $expiresHours = $this->option('expires') ?? 168; // 7 days default
        $maxUses = $this->option('max-uses') ?? 1;
        $singleUse = $this->option('single-use') || $maxUses == 1;

        try {
            $invite = OAuthInvite::createInvite(
                email: $email,
                expiresAt: now()->addHours($expiresHours),
                singleUse: $singleUse,
                maxUses: $singleUse ? 1 : $maxUses
            );

            $this->info('OAuth invite created successfully!');
            $this->table(
                ['Field', 'Value'],
                [
                    ['Code', $invite->code],
                    ['Email', $invite->email ?? 'Any email'],
                    ['Expires At', $invite->expires_at->format('Y-m-d H:i:s')],
                    ['Max Uses', $invite->max_uses],
                    ['Single Use', $invite->single_use ? 'Yes' : 'No'],
                ]
            );

            $this->line('');
            $this->info('Share this invite URL:');
            $loginUrl = url('/login?invite_code=' . $invite->code);
            $this->line($loginUrl);

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create invite: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * List OAuth invites
     */
    private function listInvites()
    {
        $includeExpired = $this->option('expired');
        
        $query = OAuthInvite::query();
        
        if (!$includeExpired) {
            $query->where('expires_at', '>', now());
        }
        
        $invites = $query->orderBy('created_at', 'desc')->get();

        if ($invites->isEmpty()) {
            $this->info('No invites found.');
            return 0;
        }

        $headers = ['Code', 'Email', 'Status', 'Uses', 'Expires At', 'Created At'];
        $rows = [];

        foreach ($invites as $invite) {
            $status = 'Active';
            if ($invite->expires_at < now()) {
                $status = 'Expired';
            } elseif ($invite->single_use && $invite->used_count > 0) {
                $status = 'Used';
            } elseif ($invite->used_count >= $invite->max_uses) {
                $status = 'Exhausted';
            }

            $rows[] = [
                substr($invite->code, 0, 12) . '...',
                $invite->email ?? 'Any',
                $status,
                $invite->used_count . '/' . $invite->max_uses,
                $invite->expires_at->format('Y-m-d H:i'),
                $invite->created_at->format('Y-m-d H:i'),
            ];
        }

        $this->table($headers, $rows);
        $this->info('Total invites: ' . $invites->count());

        return 0;
    }

    /**
     * Revoke an OAuth invite
     */
    private function revokeInvite()
    {
        $code = $this->option('code');
        if (!$code) {
            $code = $this->ask('Enter the invite code to revoke');
        }

        if (!$code) {
            $this->error('Invite code is required.');
            return 1;
        }

        $invite = OAuthInvite::where('code', $code)->first();
        if (!$invite) {
            $this->error('Invite code not found.');
            return 1;
        }

        if ($this->confirm('Are you sure you want to revoke this invite?')) {
            $invite->delete();
            $this->info('Invite revoked successfully.');
        } else {
            $this->info('Revocation cancelled.');
        }

        return 0;
    }

    /**
     * Cleanup expired invites
     */
    private function cleanupInvites()
    {
        $expiredCount = OAuthInvite::where('expires_at', '<', now())->count();
        
        if ($expiredCount === 0) {
            $this->info('No expired invites to clean up.');
            return 0;
        }

        $this->info("Found {$expiredCount} expired invite(s).");
        
        if ($this->confirm('Do you want to delete all expired invites?')) {
            $deleted = OAuthInvite::where('expires_at', '<', now())->delete();
            $this->info("Deleted {$deleted} expired invite(s).");
        } else {
            $this->info('Cleanup cancelled.');
        }

        return 0;
    }
}
