<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class InvoiceExport implements FromCollection , WithHeadings, WithEvents
{
    use RegistersEventListeners;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            'Mã dịch vụ',
            'Tên dịch vụ',
            'Số lượng bán ra',
            'Tổng thu',
        ];
    }

    public static function afterSheet(AfterSheet $event){
        // Đặt tiêu đề bảng thống kê vào ô A1
        $event->sheet->setCellValue('A1', 'BẢNG THỐNG KÊ HÓA ĐƠN ');
        $event->sheet->mergeCells('A1:E1');
        $event->sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Đặt tiêu đề cột vào ô A2
        $event->sheet->setCellValue('A2', 'Mã hóa đơns');
        $event->sheet->setCellValue('B2', 'Tên khách hàng');
        $event->sheet->setCellValue('C2', 'Tổng tiền');
        $event->sheet->setCellValue('D2', 'Phương thức thanh toán');
        $event->sheet->setCellValue('E2', 'Người thu tiền');
        $event->sheet->getStyle('A2:E2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Đặt chiều cao cho hàng tiêu đề
        $event->sheet->getRowDimension(1)->setRowHeight(30); // Chiều cao tiêu đề bảng thống kê
        $event->sheet->getRowDimension(2)->setRowHeight(20); // Chiều cao tiêu đề cột

        // Đảm bảo không có định dạng hoặc nội dung cũ gây ảnh hưởng
        $event->sheet->getDelegate()->getStyle('A1:E' . $event->sheet->getDelegate()->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Định dạng hàng dữ liệu bắt đầu từ hàng 3
        $lastRow = $event->sheet->getDelegate()->getHighestRow();
        $event->sheet->getStyle('A3:E' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Định dạng số trong cột
        $event->sheet->getDelegate()->getStyle('C3:E' . $lastRow)->getNumberFormat()
            ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);

        // Điều chỉnh kích thước cột tự động
        foreach (range('A', 'E') as $columnID) {
            $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
