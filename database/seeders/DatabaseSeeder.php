<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tenant
        $tenantId = DB::table('tenants')->insertGetId([
            'name'       => config('app.name', 'Experl Logistics'),
            'slug'       => 'experl',
            'plan'       => 'enterprise',
            'is_active'  => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Merkez Şube
        $branchId = DB::table('branches')->insertGetId([
            'tenant_id'   => $tenantId,
            'name'        => 'Merkez',
            'code'        => 'HQ',
            'address'     => '-',
            'city'        => 'Istanbul',
            'country'     => 'TR',
            'postal_code' => '34000',
            'type'        => 'branch',
            'is_active'   => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Admin kullanıcı - .env'den al, yoksa varsayılan
        DB::table('users')->insert([
            'tenant_id'   => $tenantId,
            'branch_id'   => $branchId,
            'name'        => env('ADMIN_NAME', 'Admin'),
            'email'       => env('ADMIN_EMAIL', 'admin@experl.com'),
            'password'    => Hash::make(env('ADMIN_PASSWORD', 'Experl@2024!')),
            'role'        => 'admin',
            'is_active'   => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }
}
