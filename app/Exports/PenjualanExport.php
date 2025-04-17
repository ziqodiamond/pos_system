<?php

namespace App\Exports;

use App\Models\Penjualan;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Http\Request;

class PenjualanExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $request;
    protected $filter;

    /**
     * Constructor untuk menyimpan request
     * 
     * @param Request $request - Request dari pengguna yang berisi filter dan parameter lainnya
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        // Mengambil filter dari request
        $this->filter = [
            'tanggal_mulai' => $request->tanggal_mulai ?? Carbon::now()->startOfMonth()->format('Y-m-d'),
            'tanggal_akhir' => $request->tanggal_akhir ?? Carbon::now()->format('Y-m-d'),
            'kasir_id' => $request->kasir_id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'sort_by' => $request->sort_by ?? 'created_at',
            'sort_order' => $request->sort_order ?? 'desc',
        ];
    }

    /**
     * Mengambil data yang akan diexport ke Excel
     * 
     * @return \Illuminate\Support\Collection - Koleksi data penjualan
     */
    public function collection()
    {
        // Query data penjualan berdasarkan filter
        $query = Penjualan::with(['details', 'kasir', 'customer'])
            ->whereDate('created_at', '>=', $this->filter['tanggal_mulai'])
            ->whereDate('created_at', '<=', $this->filter['tanggal_akhir']);

        // Filter berdasarkan kasir jika ada
        if (!empty($this->filter['kasir_id'])) {
            $query->where('kasir_id', $this->filter['kasir_id']);
        }

        // Filter berdasarkan metode pembayaran jika ada
        if (!empty($this->filter['metode_pembayaran'])) {
            $query->where('metode_pembayaran', $this->filter['metode_pembayaran']);
        }

        // Urutkan data sesuai dengan filter
        $query->orderBy($this->filter['sort_by'], $this->filter['sort_order']);

        // Ambil dan kembalikan data penjualan
        return $query->get();
    }

    /**
     * Mendefinisikan judul untuk worksheet Excel
     * 
     * @return string - Judul worksheet
     */
    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    /**
     * Mendefinisikan header kolom untuk file Excel
     * 
     * @return array - Array berisi judul kolom
     */
    public function headings(): array
    {
        return [
            'No. Referensi',
            'Tanggal',
            'Kasir',
            'Customer',
            'Metode Pembayaran',
            'Total Barang',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Grand Total',
        ];
    }

    /**
     * Memetakan setiap data penjualan ke format yang akan ditampilkan di Excel
     * 
     * @param mixed $penjualan - Data penjualan yang akan diformat
     * @return array - Array berisi data yang diformat
     */
    public function map($penjualan): array
    {
        // Hitung total barang dari detail penjualan
        $totalBarang = $penjualan->details->sum('kuantitas');

        return [
            $penjualan->no_ref,
            Carbon::parse($penjualan->created_at)->format('d/m/Y H:i'),
            $penjualan->kasir->name ?? 'Tidak Diketahui',
            $penjualan->customer->nama ?? 'Umum',
            $penjualan->metode_pembayaran,
            $totalBarang,
            number_format($penjualan->subtotal, 0, ',', '.'),
            number_format($penjualan->total_diskon, 0, ',', '.'),
            number_format($penjualan->total_pajak, 0, ',', '.'),
            number_format($penjualan->grand_total, 0, ',', '.'),
        ];
    }

    /**
     * Mengatur style untuk worksheet Excel
     * 
     * @param Worksheet $sheet - Worksheet yang akan dimodifikasi stylenya
     */
    public function styles(Worksheet $sheet)
    {
        // Mengatur style untuk header
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2EFDA',
                ],
            ],
        ]);

        // Menambahkan baris informasi filter di atas tabel
        $sheet->insertNewRowBefore(1, 5);

        // Judul laporan
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Informasi periode
        $tanggalMulai = Carbon::parse($this->filter['tanggal_mulai'])->format('d/m/Y');
        $tanggalAkhir = Carbon::parse($this->filter['tanggal_akhir'])->format('d/m/Y');
        $sheet->setCellValue('A2', "Periode: {$tanggalMulai} s/d {$tanggalAkhir}");
        $sheet->mergeCells('A2:J2');

        // Informasi filter kasir
        if (!empty($this->filter['kasir_id'])) {
            $namaKasir = User::find($this->filter['kasir_id'])->name ?? 'Tidak Ditemukan';
            $sheet->setCellValue('A3', "Kasir: {$namaKasir}");
        } else {
            $sheet->setCellValue('A3', "Kasir: Semua");
        }
        $sheet->mergeCells('A3:J3');

        // Informasi filter metode pembayaran
        if (!empty($this->filter['metode_pembayaran'])) {
            $sheet->setCellValue('A4', "Metode Pembayaran: {$this->filter['metode_pembayaran']}");
        } else {
            $sheet->setCellValue('A4', "Metode Pembayaran: Semua");
        }
        $sheet->mergeCells('A4:J4');

        // Informasi tanggal cetak
        $sheet->setCellValue('A5', "Dicetak pada: " . Carbon::now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A5:J5');

        // Menambahkan border ke seluruh tabel data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A6:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Menerapkan format angka untuk kolom numerik (kolom G-J)
        $sheet->getStyle('G7:J' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');

        // Tambahkan total di baris terakhir
        $newLastRow = $lastRow + 1;
        $sheet->setCellValue('A' . $newLastRow, 'TOTAL');
        $sheet->mergeCells('A' . $newLastRow . ':E' . $newLastRow);
        $sheet->getStyle('A' . $newLastRow)->getFont()->setBold(true);

        // Menghitung total dari kolom F-J
        $sheet->setCellValue('F' . $newLastRow, '=SUM(F7:F' . $lastRow . ')'); // Total Barang
        $sheet->setCellValue('G' . $newLastRow, '=SUM(G7:G' . $lastRow . ')'); // Subtotal
        $sheet->setCellValue('H' . $newLastRow, '=SUM(H7:H' . $lastRow . ')'); // Diskon
        $sheet->setCellValue('I' . $newLastRow, '=SUM(I7:I' . $lastRow . ')'); // Pajak
        $sheet->setCellValue('J' . $newLastRow, '=SUM(J7:J' . $lastRow . ')'); // Grand Total

        // Menyesuaikan style untuk baris total
        $sheet->getStyle('A' . $newLastRow . ':J' . $newLastRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2EFDA',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Format angka untuk baris total
        $sheet->getStyle('F' . $newLastRow . ':J' . $newLastRow)->getNumberFormat()->setFormatCode('#,##0');
    }
}
