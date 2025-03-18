<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateEmployeeStatus extends Command
{
    protected $signature = 'employees:update-status';
    protected $description = 'Update employee status based on users deleted_at';

    public function handle()
    {
        try {
            $updated = Employee::query()
                ->join('users', 'employees.user_id', '=', 'users.id')
                ->update([
                    'employees.status' => DB::raw('CASE 
                        WHEN users.deleted_at IS NULL THEN 1 
                        ELSE 0 
                    END')
                ]);

            $this->info("Successfully updated {$updated} employee records");
        } catch (\Exception $e) {
            $this->error("Failed to update employee statuses: {$e->getMessage()}");
        }
    }
}