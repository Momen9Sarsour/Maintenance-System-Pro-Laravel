<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $equipment = [
            [
                'name' => 'وحدة تكييف مركزية - برج جدة التجاري',
                'model' => 'XA 30 كيلو حركة',
                'serial_number' => 'AC-JED-2023-003',
                'manufacturer' => 'كارير',
                'location' => 'برج جدة التجاري، حي البلدة، جدة',
                'building' => 'برج جدة التجاري',
                'floor' => 'الدور الأرضي',
                'status' => 'operational',
                'installation_date' => '2024-08-20',
                'warranty_expiry' => '2026-08-20',
                'description' => 'وحدة تكييف مركزية رئيسية',
            ],
            [
                'name' => 'مجمع الدمام الصناعي',
                'model' => 'نورفنوس NK 65-160',
                'serial_number' => 'PPM-DAM-2022-004',
                'manufacturer' => 'نورفنوس',
                'location' => 'مجمع الدمام الصناعي',
                'building' => 'المجمع الصناعي',
                'floor' => 'الدور الأرضي',
                'status' => 'operational',
                'installation_date' => '2024-07-15',
                'warranty_expiry' => '2026-07-15',
            ],
            [
                'name' => 'Air Handler Unit #1',
                'model' => 'AHU-500',
                'serial_number' => 'AHU-J-2023-001',
                'manufacturer' => 'Carrier',
                'location' => 'Building A - Rooftop',
                'building' => 'Building A',
                'floor' => 'Rooftop',
                'status' => 'operational',
                'installation_date' => '2024-07-10',
                'warranty_expiry' => '2026-07-10',
            ],
            [
                'name' => 'Chiller Unit #2',
                'model' => 'CH-800',
                'serial_number' => 'CH-JED-2023-002',
                'manufacturer' => 'Trane',
                'location' => 'Building B - Basement',
                'building' => 'Building B',
                'floor' => 'Basement',
                'status' => 'out_of_service',
                'installation_date' => '2023-05-15',
                'warranty_expiry' => '2025-05-15',
            ],
            [
                'name' => 'Air Handler Unit #2',
                'model' => 'AHU-400',
                'serial_number' => 'AHU-DAM-2022-005',
                'manufacturer' => 'Trane',
                'location' => 'Building C - 2nd Floor',
                'building' => 'Building C',
                'floor' => '2nd Floor',
                'status' => 'maintenance',
                'installation_date' => '2024-06-01',
                'warranty_expiry' => '2026-06-01',
            ],
        ];

        foreach ($equipment as $item) {
            Equipment::create($item);
        }
    }
}
