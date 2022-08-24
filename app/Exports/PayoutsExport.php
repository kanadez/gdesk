<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PayoutsExport extends DefaultValueBinder implements FromCollection, WithColumnFormatting, ShouldAutoSize, WithHeadings, WithCustomValueBinder, WithStyles
{
    protected $payouts;

    public function __construct($payouts)
    {
        $this->payouts = $payouts;
    }

    public function columnFormats(): array {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_NUMBER_00,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD
        ];
    }

    public function collection()
    {
        return $this->payouts->map(function($item) {
            return [
                'id' => $item->id,
                'category_title' => $item->category_title,
                'paysystem_title' => $item->paysystem_title,
                'payment_method_title' => $item->payment_method_title,
                'amount' => $item->amount,
                'address' => $item->address,
                'comment' => $item->comment,
                'created_at' => $item->created_at,
                'status' => $item->status,
                'is_internal' => $item->is_internal ? 'Внутр.' : '',
            ];
        });
    }


    public function headings(): array
    {
        return [
            'A' => 'ID',
            'B' => 'Категория',
            'C' => 'Система',
            'D' => 'Способ',
            'E' => 'Сумма',
            'F' => 'Реквизиты',
            'G' => 'Комментарий',
            'H' => 'Дата создания',
            'I' => 'Статус',
            'J' => 'Внутренний'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
            'A:J' => ['alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],]
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if($cell->getRow() > 1 && in_array($cell->getColumn(),  ['A', 'E'])) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
            return true;
        }

        return $cell->setValueExplicit($value, DataType::TYPE_STRING);
    }
}
