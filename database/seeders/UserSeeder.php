<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@maintenance.com',
            'password' => Hash::make('123456789'),
            'role' => 'admin',
            'phone' => '+966 50 000 0000',
            'address' => 'Riyadh, Saudi Arabia',
            'is_active' => true,
        ]);

        // Manager
        User::create([
            'name' => 'Maintenance Manager',
            'email' => 'manager@maintenance.com',
            'password' => Hash::make('123456789'),
            'role' => 'manager',
            'phone' => '+966 50 111 1111',
            'address' => 'Jeddah, Saudi Arabia',
            'company_name' => 'MaintenancePro',
            'is_active' => true,
        ]);

        // Technicians
        $technicians = [
            [
                'name' => 'Demo Technician',
                'email' => 'tech@maintenance.com',
                'specialization' => 'HVAC Systems',
                'phone' => '+966 55 001 00303',
            ],
            [
                'name' => 'Sdiri Faouzi',
                'email' => 'f.sdiri@gmail.com',
                'specialization' => 'Electronic',
                'phone' => '+966 55 001 12233',
            ],
            [
                'name' => 'Benour Ali',
                'email' => 'ali@gmail.com',
                'specialization' => 'Electronic',
                'phone' => '+966 55 001 12255',
            ],
            [
                'name' => 'Salem Al-Mutairi',
                'email' => 'salem@maintenance.sa',
                'specialization' => 'الكهرباء و أنظمة الطاقة',
                'phone' => '+966 53 789 0123',
            ],
            [
                'name' => 'Leonardo Paolo',
                'email' => 'paolo@gmail.com',
                'specialization' => 'Mechanical',
                'phone' => '+966 50 678 9012',
            ],
        ];

        foreach ($technicians as $tech) {
            $user = User::create([
                'name' => $tech['name'],
                'email' => $tech['email'],
                'password' => Hash::make('123456789'),
                'role' => 'technician',
                'phone' => $tech['phone'],
                'address' => 'Saudi Arabia',
                'is_active' => true,
            ]);

            // Create technician profile
            \App\Models\Technician::create([
                'user_id' => $user->id,
                'specialization' => $tech['specialization'],
                'status' => 'available',
                'latitude' => 21.543300,
                'longitude' => 39.172800,
                'rating' => 4.5,
                'tasks_completed' => rand(30, 50),
                'first_time_fix_rate' => 89.5,
                'on_time_rate' => 92.0,
                'avg_repair_time' => rand(90, 120),
            ]);
        }

        // Clients
        $clients = [
            [
                'name' => 'DECLEAN LTD',
                'email' => 'clean@gmail.com',
                'company_name' => 'DECLEAN LTD',
                'phone' => '+966 55 223 6366',
            ],
            [
                'name' => 'FRIZE Paolo',
                'email' => 'glissinajib@gmail.com',
                'company_name' => null,
                'phone' => '+966 55 223 36699',
            ],
            [
                'name' => 'John Smith',
                'email' => 'john.smith@acme.com',
                'company_name' => 'Acme Corporation',
                'phone' => '+1 555-0300',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@techcorp.com',
                'company_name' => 'TechCorp Inc.',
                'phone' => '+1 555-0400',
            ],
            [
                'name' => 'عبدالرحمن الشمري',
                'email' => 'abdulrahman@ast.sa',
                'company_name' => 'شركة أرامكو للخدمات التقنية',
                'phone' => '+966 13 678 9012',
            ],
            [
                'name' => 'عبدالعزيز المالكي',
                'email' => 'aziz@makkahhotels.sa',
                'company_name' => 'فنادق مكة الدولية',
                'phone' => '+966 12 789 0123',
            ],
            [
                'name' => 'هند القرشي',
                'email' => 'hind@mmc.sa',
                'company_name' => 'مجمع المدينة الطبي',
                'phone' => '+966 14 890 1234',
            ],
        ];

        foreach ($clients as $client) {
            User::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'password' => Hash::make('123456789'),
                'role' => 'client',
                'phone' => $client['phone'],
                'company_name' => $client['company_name'],
                'address' => 'Saudi Arabia',
                'is_active' => true,
            ]);
        }

        // Data Entry
        User::create([
            'name' => 'Data Entry',
            'email' => 'dataentry@maintenance.com',
            'password' => Hash::make('123456789'),
            'role' => 'data_entry',
            'phone' => '+966 50 222 2222',
            'is_active' => true,
        ]);
    }
}
