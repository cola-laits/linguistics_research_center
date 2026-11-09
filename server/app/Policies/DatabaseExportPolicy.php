<?php

namespace App\Policies;

use App\Models\User;

class DatabaseExportPolicy
{
    public function export(User $user): bool
    {
        return $user->can('export_database');
    }
}
