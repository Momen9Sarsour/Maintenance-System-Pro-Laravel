<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\WorkOrder;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $workOrders = WorkOrder::whereNotNull('price')->get();
        $clients = User::where('role', 'client')->get();

        $invoices = [
            [
                'invoice_number' => 'INV-2024-SA-003',
                'work_order_id' => $workOrders[0]->id ?? 1,
                'client_id' => $clients[4]->id ?? 1,
                'amount' => 6670.00,
                'tax' => 15.00,
                'total_amount' => 7670.50,
                'status' => 'sent',
                'issue_date' => '2026-03-24',
                'due_date' => '2026-04-30',
                'payment_method' => null,
                'notes' => 'Invoice for chiller repair',
            ],
            [
                'invoice_number' => 'INV-2024-SA-004',
                'work_order_id' => $workOrders[1]->id ?? 1,
                'client_id' => $clients[5]->id ?? 1,
                'amount' => 2760.00,
                'tax' => 15.00,
                'total_amount' => 3174.00,
                'status' => 'draft',
                'issue_date' => '2026-04-23',
                'due_date' => '2026-04-29',
                'payment_method' => null,
                'notes' => 'Pending approval',
            ],
            [
                'invoice_number' => 'INV-2024-001',
                'work_order_id' => $workOrders[2]->id ?? 1,
                'client_id' => $clients[2]->id ?? 1,
                'amount' => 1650.00,
                'tax' => 10.00,
                'total_amount' => 1815.00,
                'status' => 'paid',
                'issue_date' => '2026-02-21',
                'due_date' => '2026-03-28',
                'paid_date' => '2026-03-25',
                'payment_method' => 'credit_card',
                'notes' => 'Payment completed',
            ],
            [
                'invoice_number' => 'INV-2024-002',
                'work_order_id' => $workOrders[3]->id ?? 1,
                'client_id' => $clients[3]->id ?? 1,
                'amount' => 3520.00,
                'tax' => 10.00,
                'total_amount' => 3872.00,
                'status' => 'draft',
                'issue_date' => '2026-03-23',
                'due_date' => '2026-04-22',
                'payment_method' => null,
                'notes' => 'Awaiting client approval',
            ],
        ];

        foreach ($invoices as $invoice) {
            Invoice::create($invoice);
        }
    }
}
