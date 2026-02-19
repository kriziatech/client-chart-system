<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MultiTenancyDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Companies using DB facade
        $techCorpId = $this->getOrCreateCompanyId([
            'name' => 'TechCorp Solutions',
            'domain' => 'techcorp',
            'email' => 'admin@techcorp.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $designStudioId = $this->getOrCreateCompanyId([
            'name' => 'Elite Design Studio',
            'domain' => 'designstudio',
            'email' => 'admin@designstudio.com',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Ensure Admin Role Exists
        $adminRole = DB::table('roles')->where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRoleId = DB::table('roles')->insertGetId([
                'name' => 'Admin',
                'type' => 'admin',
                'description' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $adminRoleId = $adminRole->id;
        }

        // 3. Create Users
        $this->createAdminUserIfNotExists('admin@techcorp.com', $techCorpId, 'TechCorp Admin', $adminRoleId);
        $this->createAdminUserIfNotExists('admin@designstudio.com', $designStudioId, 'DesignStudio Admin', $adminRoleId);

        // 4. Create Isolated Data (Clients)
        $this->createClientIfNotExists($techCorpId, 'TechCorp Exclusive Client', 'TC-001');
        $this->createClientIfNotExists($techCorpId, 'Another TechCorp Client', 'TC-O-002');
        $this->createClientIfNotExists($designStudioId, 'DesignStudio VIP Client', 'DS-001');
    }

    private function getOrCreateCompanyId(array $attributes)
    {
        // Check including soft deletes
        $exists = DB::table('companies')->where('domain', $attributes['domain'])->first();
        
        if ($exists) {
            // Restore if trashed
            if ($exists->deleted_at) {
                DB::table('companies')->where('id', $exists->id)->update(['deleted_at' => null]);
            }
            return $exists->id;
        } else {
            return DB::table('companies')->insertGetId($attributes);
        }
    }

    private function createAdminUserIfNotExists($email, $companyId, $name, $roleId)
    {
        $exists = DB::table('users')->where('email', $email)->first();
        
        if ($exists) {
             if ($exists->deleted_at) {
                DB::table('users')->where('id', $exists->id)->update(['deleted_at' => null]);
            }
            // Update company_id to ensure it matches
            DB::table('users')->where('id', $exists->id)->update(['company_id' => $companyId]);
        } else {
            DB::table('users')->insert([
                'company_id' => $companyId,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => $roleId,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function createClientIfNotExists($companyId, $name, $fileNumber)
    {
        $exists = DB::table('clients')->where('file_number', $fileNumber)->first();

        if (!$exists) {
            DB::table('clients')->insert([
                'company_id' => $companyId,
                'first_name' => $name,
                'file_number' => $fileNumber,
                'uuid' => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}