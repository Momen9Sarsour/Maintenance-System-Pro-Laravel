<?php

namespace Database\Seeders;

use App\Models\SparePart;
use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        $parts = [
            [
                'name' => 'HVAC Air Filter',
                'sku' => 'AF-HERV11-16',
                'category' => 'Filters',
                'stock_quantity' => 24,
                'min_stock' => 10,
                'max_stock' => 50,
                'price' => 18.50,
                'supplier' => 'FilterPro Supplies',
                'location' => 'Warehouse A - Shelf 3',
                'warehouse' => 'Warehouse A',
                'shelf' => 'Shelf 3',
            ],
            [
                'name' => 'Refrigerant R-410A',
                'sku' => 'REF-R410A-25LB',
                'category' => 'Refrigerants',
                'stock_quantity' => 4,
                'min_stock' => 5,
                'max_stock' => 20,
                'price' => 145.00,
                'supplier' => 'CoolTech Refrigerants',
                'location' => 'Warehouse B - Refrigerant Bay',
                'warehouse' => 'Warehouse B',
                'shelf' => 'Refrigerant Bay',
            ],
            [
                'name' => 'Pump Seal Kit',
                'sku' => 'PSK-CH800-STD',
                'category' => 'Seals',
                'stock_quantity' => 2,
                'min_stock' => 2,
                'max_stock' => 10,
                'price' => 89.00,
                'supplier' => 'Trane Parts Direct',
                'location' => 'Warehouse A - Shelf 7',
                'warehouse' => 'Warehouse A',
                'shelf' => 'Shelf 7',
            ],
            [
                'name' => 'زيت تبرید شيل فريجوليال',
                'sku' => 'SH-FRG-20L',
                'category' => 'Oils',
                'stock_quantity' => 40,
                'min_stock' => 20,
                'max_stock' => 100,
                'price' => 45.00,
                'supplier' => 'شركة شيل للمواد التشحيمية',
                'location' => 'مستودع جدة - رف 1',
                'warehouse' => 'مستودع جدة',
                'shelf' => 'رف 1',
            ],
            [
                'name' => 'NK GF-FNK-SEAL-75',
                'sku' => 'GF-FNK-SEAL-75',
                'category' => 'Seals',
                'stock_quantity' => 3,
                'min_stock' => 2,
                'max_stock' => 8,
                'price' => 320.00,
                'supplier' => 'مؤسسة الخليج للصناعات والأنظمة الهيدروليكية',
                'location' => 'مستودع الدمار - رف ب 2',
                'warehouse' => 'مستودع الدمار',
                'shelf' => 'رف ب 2',
            ],
        ];

        foreach ($parts as $part) {
            SparePart::create($part);
        }
    }
}
