<?php

namespace App\Exports;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;

class PembelianExport implements WithMultipleSheets
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        $format = $this->request->input('format', 'detail');

        // Sheet Data Pembelian (Data Utama)
        $sheets[] = new PembelianDataSheet($this->request);

        // Sheet Rincian Harian
        $sheets[] = new PembelianRincianHarianSheet($this->request);

        // Sheet Top Barang
        $sheets[] = new PembelianTopBarangSheet($this->request);

        return $sheets;
    }
}

/**
 * Sheet untuk data pembelian utama
 */
class PembelianDataSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithMapping, WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Inisialisasi tanggal awal dan akhir dari request
        $startDate = $this->request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $this->request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        $status = $this->request->input('status', '');
        $supplierId = $this->request->input('supplier_id');

        // Query dasar pembelian
        $pembelianQuery = Pembelian::with(['supplier', 'details.barang'])
            ->whereBetween('tanggal_pembelian', [$startDate, $endDate])
            ->orderBy('tanggal_pembelian', 'desc');

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            $pembelianQuery->where('status', $status);
        }

        // Filter berdasarkan supplier jika ada
        if (!empty($supplierId)) {
            $pembelianQuery->where('supplier_id', $supplierId);
        }

        // Ambil data pembelian
        return $pembelianQuery->get();
    }

    public function map($pembelian): array
    {
        // Menghitung total barang dalam pembelian ini
        $totalBarang = $pembelian->details->sum('qty_base');

        // Konversi nilai uang dari sen ke nilai yang sebenarnya (dibagi 100)
        return [
            $pembelian->no_ref,
            Carbon::parse($pembelian->tanggal_pembelian)->format('d-m-Y'),
            $pembelian->supplier ? $pembelian->supplier->nama : 'Tidak Ada',
            $pembelian->status,
            $totalBarang,
            $pembelian->subtotal / 100, // Konversi sen ke nilai sebenarnya
            $pembelian->diskon_value / 100, // Konversi sen ke nilai sebenarnya
            $pembelian->pajak_value / 100, // Konversi sen ke nilai sebenarnya
            $pembelian->biaya_lainnya / 100, // Konversi sen ke nilai sebenarnya
            $pembelian->total / 100, // Konversi sen ke nilai sebenarnya
            $pembelian->keterangan
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Transaksi',
            'Tanggal',
            'Supplier',
            'Status',
            'Total Barang',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Biaya Lainnya',
            'Total',
            'Keterangan'
        ];
    }

    public function title(): string
    {
        return 'Data Pembelian';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
        ];
    }
}

/**
 * Sheet untuk rincian harian
 */
class PembelianRincianHarianSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Inisialisasi tanggal awal dan akhir dari request
        $startDate = $this->request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $this->request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        $status = $this->request->input('status', '');
        $supplierId = $this->request->input('supplier_id');

        // Query dasar pembelian
        $pembelianQuery = Pembelian::with(['details.barang'])
            ->whereBetween('tanggal_pembelian', [$startDate, $endDate])
            ->orderBy('tanggal_pembelian', 'desc');

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            $pembelianQuery->where('status', $status);
        }

        // Filter berdasarkan supplier jika ada
        if (!empty($supplierId)) {
            $pembelianQuery->where('supplier_id', $supplierId);
        }

        // Ambil data pembelian
        $pembelian = $pembelianQuery->get();

        // Buat rincian harian
        $rincianHarian = collect();
        if ($startDate && $endDate) {
            $tanggalRange = Carbon::parse($startDate)->daysUntil($endDate);

            foreach ($tanggalRange as $tanggal) {
                $tanggalFormat = $tanggal->format('Y-m-d');
                $pembelianHarian = $pembelian->filter(function ($p) use ($tanggalFormat) {
                    return Carbon::parse($p->tanggal_pembelian)->format('Y-m-d') === $tanggalFormat;
                });

                $totalBarang = 0;
                foreach ($pembelianHarian as $p) {
                    if ($p->details) {
                        $totalBarang += $p->details->sum('qty_base');
                    }
                }

                $rincianHarian->push([
                    'tanggal' => $tanggal->format('d-m-Y'),
                    'jumlah_transaksi' => $pembelianHarian->count(),
                    'total_barang' => $totalBarang,
                    'subtotal' => $pembelianHarian->sum('subtotal') / 100, // Konversi sen ke nilai sebenarnya
                    'diskon' => $pembelianHarian->sum('diskon_value') / 100, // Konversi sen ke nilai sebenarnya
                    'pajak' => $pembelianHarian->sum('pajak_value') / 100, // Konversi sen ke nilai sebenarnya
                    'biaya_lainnya' => $pembelianHarian->sum('biaya_lainnya') / 100, // Konversi sen ke nilai sebenarnya
                    'total_pembelian' => $pembelianHarian->sum('total') / 100 // Konversi sen ke nilai sebenarnya
                ]);
            }
        }

        return $rincianHarian;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jumlah Transaksi',
            'Total Barang',
            'Subtotal',
            'Diskon',
            'Pajak',
            'Biaya Lainnya',
            'Total Pembelian'
        ];
    }

    public function title(): string
    {
        return 'Rincian Harian';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
        ];
    }
}

/**
 * Sheet untuk top barang
 */
class PembelianTopBarangSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Inisialisasi tanggal awal dan akhir dari request
        $startDate = $this->request->input('tanggal_mulai', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $this->request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        $status = $this->request->input('status', '');
        $supplierId = $this->request->input('supplier_id');

        // Ambil data barang terbanyak dibeli
        $barangTerbanyak = DetailPembelian::with('barang')
            ->whereHas('pembelian', function ($query) use ($startDate, $endDate, $status, $supplierId) {
                $query->whereBetween('tanggal_pembelian', [$startDate, $endDate]);
                if (!empty($status)) {
                    $query->where('status', $status);
                }
                if (!empty($supplierId)) {
                    $query->where('supplier_id', $supplierId);
                }
            })
            ->select('barang_id', DB::raw('SUM(qty_base) as total_qty'), DB::raw('SUM(total) as total_value'))
            ->groupBy('barang_id')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        // Transformasi data untuk laporan
        $hasil = collect();
        foreach ($barangTerbanyak as $index => $item) {
            $hasil->push([
                'no' => $index + 1,
                'kode' => $item->barang ? $item->barang->kode : 'Tidak Ada',
                'nama' => $item->barang ? $item->barang->nama : 'Tidak Ada',
                'jumlah' => $item->total_qty,
                'satuan' => $item->barang ? $item->barang->satuan_base : '-',
                'total_nilai' => $item->total_value / 100, // Konversi sen ke nilai sebenarnya
            ]);
        }

        return $hasil;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Satuan',
            'Total Nilai'
        ];
    }

    public function title(): string
    {
        return 'Top 10 Barang';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2, // 2 desimal untuk mata uang
        ];
    }
}
