<?php

namespace App\Exports;

use App\Models\Sale;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SalesExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            new SalesSummarySheet($this->startDate, $this->endDate),
            new SalesDetailsSheet($this->startDate, $this->endDate),
        ];
    }
}

class SalesSummarySheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Sale::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['details.product', 'details.product.category'])
            ->get();
    }

    public function title(): string
    {
        return 'Satış Özeti';
    }

    public function headings(): array
    {
        return [
            'Tarih',
            'Sipariş No',
            'Toplam Tutar',
            'Ürün Sayısı',
            'Ortalama Ürün Fiyatı'
        ];
    }

    public function map($sale): array
    {
        $totalAmount = $sale->details->sum('total_price');
        $totalQuantity = $sale->details->sum('quantity');
        $averagePrice = $totalQuantity > 0 ? $totalAmount / $totalQuantity : 0;

        return [
            $sale->created_at->format('d.m.Y H:i'),
            $sale->id,
            number_format($totalAmount, 2) . ' ₺',
            $totalQuantity,
            number_format($averagePrice, 2) . ' ₺'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]
        ];
    }
}

class SalesDetailsSheet implements FromCollection, WithTitle, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Sale::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->with(['details.product', 'details.product.category'])
            ->get();
    }

    public function title(): string
    {
        return 'Ürün Detayları';
    }

    public function headings(): array
    {
        return [
            'Tarih',
            'Sipariş No',
            'Ürün',
            'Kategori',
            'Adet',
            'Birim Fiyat',
            'Toplam Tutar'
        ];
    }

    public function map($sale): array
    {
        $rows = [];
        foreach ($sale->details as $detail) {
            $productName = $detail->product ? $detail->product->name : 'Silinmiş Ürün';
            $categoryName = $detail->product && $detail->product->category ? 
                $detail->product->category->name : 'Diğer';
                
            $rows[] = [
                $sale->created_at->format('d.m.Y H:i'),
                $sale->id,
                $productName,
                $categoryName,
                $detail->quantity,
                number_format($detail->price, 2) . ' ₺',
                number_format($detail->total_price, 2) . ' ₺'
            ];
        }
        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ],
            2 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]
        ];
    }
} 