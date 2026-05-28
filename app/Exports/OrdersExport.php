<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        private readonly ?string $startDate,
        private readonly ?string $endDate,
        private readonly ?string $filterStatus,
        private readonly ?string $filterPayment,
    ) {}

    public function query()
    {
        return Order::query()
            ->with(['table', 'items.menu'])
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate,   fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->filterStatus,  fn($q) => $q->where('order_status',  $this->filterStatus))
            ->when($this->filterPayment, fn($q) => $q->where('payment_status', $this->filterPayment))
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'Kode Order',
            'Meja',
            'Item Pesanan',
            'Total (Rp)',
            'Status Order',
            'Status Pembayaran',
            'Metode Bayar',
            'Tanggal',
            'Jam',
        ];
    }

    public function map($order): array
    {
        $items = $order->items->map(fn($i) => "{$i->quantity}x {$i->menu->name}")->implode(', ');

        return [
            $order->order_code,
            $order->table->table_number,
            $items,
            $order->total_price,
            $order->order_status,
            $order->payment_status,
            $order->payment_method ?? '-',
            $order->created_at->format('d/m/Y'),
            $order->created_at->format('H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row bold + background
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FF0A0A0A']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFC8FF00'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }
}
