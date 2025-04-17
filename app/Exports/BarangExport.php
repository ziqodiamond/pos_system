<?php

namespace App\Exports;

use App\Models\Barang;
use App\Models\Setting;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class BarangExport implements FromCollection, WithHeadings, WithStyles, WithMapping, WithCustomStartCell
{
    // Variabel untuk nomor baris
    protected $no = 1;
    // Variabel untuk menyimpan request
    protected $request;
    // Variabel untuk data toko
    protected $toko;

    /**
     * Konstruktor untuk menerima parameter request
     * 
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->toko = $this->getTokoData();
    }

    /**
     * Mendapatkan data pengaturan toko dari database
     * 
     * @return array
     */
    protected function getTokoData()
    {
        $settingQuery = Setting::whereIn('key', ['toko_nama', 'toko_alamat', 'toko_kota', 'toko_provinsi', 'toko_telepon', 'toko_email', 'toko_npwp'])
            ->pluck('value', 'key')
            ->toArray();

        return [
            'nama' => $settingQuery['toko_nama'] ?? env('TOKO_NAMA', 'Nama Toko'),
            'alamat' => $settingQuery['toko_alamat'] ?? env('TOKO_ALAMAT', 'Alamat Toko'),
            'kota' => $settingQuery['toko_kota'] ?? env('TOKO_KOTA', 'Jakarta'),
            'provinsi' => $settingQuery['toko_provinsi'] ?? env('TOKO_PROVINSI', 'DKI Jakarta'),
            'telepon' => $settingQuery['toko_telepon'] ?? env('TOKO_TELEPON', 'Telepon Toko'),
            'email' => $settingQuery['toko_email'] ?? env('TOKO_EMAIL', 'Email Toko'),
            'npwp' => $settingQuery['toko_npwp'] ?? env('TOKO_NPWP', 'NPWP Toko'),
        ];
    }

    /**
     * Menentukan posisi mulai data (setelah informasi toko)
     * 
     * @return string
     */
    public function startCell(): string
    {
        return 'A8';
    }

    /**
     * Mendefinisikan header untuk Excel
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Kode',
            'Nama',
            'Kategori',
            'Satuan',
            'Stok',
            'Stok Min',
            'Harga Pokok',
            'Harga Jual',
            'Pajak',
            'Status',
            'Nilai Inventory'
        ];
    }

    /**
     * Memetakan data dari model ke baris Excel
     * 
     * @param mixed $barang
     * @return array
     */
    public function map($barang): array
    {
        $nilaiInventory = $barang->harga_pokok * $barang->stok;

        return [
            $this->no++,
            $barang->kode,
            $barang->nama,
            $barang->kategori ? $barang->kategori->nama : '-',
            $barang->satuan ? $barang->satuan->nama : '-',
            $barang->stok,
            $barang->stok_minimum,
            number_format($barang->harga_pokok, 0, ',', '.'),
            number_format($barang->harga_jual, 0, ',', '.'),
            $barang->pajak ? $barang->pajak->nama . ' (' . $barang->pajak->rate . '%)' : '-',
            $barang->status == 1 ? 'Aktif' : 'Tidak Aktif',
            number_format($nilaiInventory, 0, ',', '.')
        ];
    }

    /**
     * Mendapatkan data barang sesuai filter
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function collection()
    {
        // Mempersiapkan query untuk data barang
        $query = Barang::query();

        // Filter berdasarkan kategori jika ada
        if ($this->request->filled('kategori_id')) {
            $query->where('kategori_id', $this->request->kategori_id);
        }

        // Filter berdasarkan status jika ada
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        // Filter berdasarkan stok minimum jika dicentang
        if ($this->request->has('filter_stok_minimum')) {
            $query->whereRaw('stok <= stok_minimum');
        }

        // Urutkan data
        $sortBy = $this->request->input('sort_by', 'nama');
        $sortOrder = $this->request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pastikan fungsi get() dipanggil hanya satu kali
        return $query->with(['kategori', 'satuan', 'pajak'])->get();
    }

    /**
     * Mengatur style untuk worksheet Excel
     * 
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'L'; // Kolom terakhir
        $headerRow = 8;

        // Tambahkan info toko di bagian atas
        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', 'LAPORAN DATA BARANG');

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', 'Toko: ' . $this->toko['nama']);

        $sheet->mergeCells("A3:{$lastColumn}3");
        $sheet->setCellValue('A3', 'Alamat: ' . $this->toko['alamat'] . ', ' . $this->toko['kota'] . ', ' . $this->toko['provinsi']);

        $sheet->mergeCells("A4:{$lastColumn}4");
        $sheet->setCellValue('A4', 'Telepon: ' . $this->toko['telepon'] . ' | Email: ' . $this->toko['email']);

        $sheet->mergeCells("A5:{$lastColumn}5");
        $sheet->setCellValue('A5', 'NPWP: ' . $this->toko['npwp']);

        $sheet->mergeCells("A6:{$lastColumn}6");
        $sheet->setCellValue('A6', 'Tanggal Export: ' . now()->format('d-m-Y H:i:s'));

        // Baris kosong sebelum header
        $sheet->mergeCells("A7:{$lastColumn}7");
        $sheet->setCellValue('A7', '');

        // Hitung baris terakhir setelah data diisi
        $lastRow = $sheet->getHighestRow();

        // Style judul
        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
        ]);

        // Style info toko
        $sheet->getStyle("A2:A6")->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Style header kolom
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D9E1F2']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // Style isi tabel - pastikan ini dijalankan hanya jika ada data
        if ($lastRow > $headerRow) {
            $sheet->getStyle("A{$headerRow}:{$lastColumn}{$lastRow}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            ]);
        }

        // Autosize semua kolom
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
