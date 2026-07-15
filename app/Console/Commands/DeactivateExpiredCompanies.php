<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('companies:deactivate-expired')]
#[Description('Deactivate companies whose subscription has expired')]
class DeactivateExpiredCompanies extends Command
{
    public function handle(): void
    {
        $count = Company::where('status', 'active')
            ->whereNotNull('subscription_ends_at')
            ->where('subscription_ends_at', '<', now()->toDateString())
            ->update(['status' => 'inactive']);

        $this->info("Deactivated {$count} compan".($count === 1 ? 'y' : 'ies').' with an expired subscription.');
    }
}
