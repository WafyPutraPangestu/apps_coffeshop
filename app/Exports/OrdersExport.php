<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrdersExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        private readonly ?string $startDate,
        private readonly ?string $endDate,
        private readonly ?string $filterStatus,
        private readonly ?string $filterPayment,
    ) {}

    public function collection()
    {
        $orders = Order::query()
            ->with(['table', 'items.menu'])
            ->when($this->startDate, fn($q) => $q->whereDate('created_at', '>=', $this->startDate))
            ->when($this->endDate,   fn($q) => $q->whereDate('created_at', '<=', $this->endDate))
            ->when($this->filterStatus,  fn($q) => $q->where('order_status',  $this->filterStatus))
            ->when($this->filterPayment, fn($q) => $q->where('payment_status', $this->filterPayment))
            ->orderByDesc('created_at')
            ->get();

        $rows = collect();

        foreach ($orders as $order) {
            $items = $order->items;
            $itemCount = $items->count();

            foreach ($items as $index => $item) {
                $isFirst = $index === 0;
                $isLast  = $index === $itemCount - 1;

                $rows->push([
                    // Kolom info order hanya di baris pertama item
                    'Kode Order'        => $isFirst ? $order->order_code : '',
                    'Meja'              => $isFirst ? $order->table->table_number : '',
                    // Kolom per item
                    'Nama Menu'         => $item->menu->name ?? '-',
                    'Qty'               => $item->quantity,
                    'Harga Satuan (Rp)' => $item->price,
                    'Subtotal (Rp)'     => $item->price * $item->quantity,
                    // Kolom total order hanya di baris terakhir item
                    'Total Order (Rp)'  => $isLast ? $order->total_price : '',
                    'Status Order'      => $isFirst ? $order->order_status : '',
                    'Status Pembayaran' => $isFirst ? $order->payment_status : '',
                    'Metode Bayar'      => $isFirst ? ($order->payment_method ?? '-') : '',
                    'Tanggal'           => $isFirst ? $order->created_at->format('d/m/Y') : '',
                    'Jam'               => $isFirst ? $order->created_at->format('H:i') : '',
                ]);
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Kode Order',
            'Meja',
            'Nama Menu',
            'Qty',
            'Harga Satuan (Rp)',
            'Subtotal (Rp)',
            'Total Order (Rp)',
            'Status Order',
            'Status Pembayaran',
            'Metode Bayar',
            'Tanggal',
            'Jam',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row bold + background lime
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FF0A0A0A']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFC8FF00'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }
}
