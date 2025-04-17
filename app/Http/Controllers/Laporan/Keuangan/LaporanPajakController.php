<?php

namespace App\Http\Controllers\Laporan\Keuangan;

use stdClass;
use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Setting;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class LaporanPajakController extends Controller
{
    /**
     * Menampilkan halaman laporan pajak
     */
    public function index(Request $request)
    {
        // Generate breadcrumbs
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Ambil parameter bulan dan tahun dari request atau gunakan bulan dan tahun saat ini
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Format periode untuk ditampilkan
        $periode = Carbon::createFromDate($tahun, $bulan, 1)->format('F Y');

        // Dapatkan barang-barang yang terjual pada periode ini
        $barangTerjual = $this->getBarangTerjual($bulan, $tahun);

        // Ambil data pajak masukan (dari pembelian) berdasarkan barang terjual
        $pajakMasukan = $this->getPajakMasukan($bulan, $tahun, $barangTerjual);

        // Ambil data pajak keluaran (dari penjualan)
        $pajakKeluaran = $this->getPajakKeluaran($bulan, $tahun);

        // Hitung total pajak masukan dan keluaran
        $totalPajakMasukan = $pajakMasukan->sum('nilai_pajak');
        $totalPajakKeluaran = $pajakKeluaran->sum('nilai_pajak');

        // Hitung pajak yang harus dibayarkan (PPN Keluaran - PPN Masukan)
        $pajakDibayarkan = $totalPajakKeluaran - $totalPajakMasukan;

        // Data untuk grafik pajak bulanan (6 bulan terakhir)
        $grafikPajak = $this->getGrafikPajak($bulanAkhir = $bulan, $tahunAkhir = $tahun);

        // Ringkasan pajak per kategori/jenis barang
        $ringkasanPajakPerKategori = $this->getRingkasanPajakPerKategori($bulan, $tahun);

        return view('laporan.keuangan.pajak', compact(
            'breadcrumbs',
            'periode',
            'bulan',
            'tahun',
            'pajakMasukan',
            'pajakKeluaran',
            'totalPajakMasukan',
            'totalPajakKeluaran',
            'pajakDibayarkan',
            'grafikPajak',
            'ringkasanPajakPerKategori'
        ));
    }

    /**
     * Dapatkan daftar barang yang terjual pada periode tertentu
     * Ini digunakan untuk menyaring pajak masukan yang relevan
     */
    private function getBarangTerjual($bulan, $tahun)
    {
        return DB::table('detail_penjualans')
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->whereMonth('penjualans.created_at', $bulan)
            ->whereYear('penjualans.created_at', $tahun)
            ->select(
                'detail_penjualans.barang_id',
                DB::raw('SUM(detail_penjualans.kuantitas) as total_terjual')
            )
            ->groupBy('detail_penjualans.barang_id')
            ->pluck('total_terjual', 'barang_id')
            ->toArray();
    }

    /**
     * Ambil data pajak masukan dari pembelian
     * Hanya memperhitungkan barang yang juga terjual pada periode ini
     * Menggunakan sistem FIFO (First In First Out)
     * 
     * PERBAIKAN: Mengkonversi array ke objek stdClass untuk kompatibilitas dengan view
     */
    private function getPajakMasukan($bulan, $tahun, $barangTerjual)
    {
        // Hasil akhir pajak masukan
        $hasilPajakMasukan = collect();

        // Iterasi untuk setiap barang yang terjual
        foreach ($barangTerjual as $barangId => $jumlahTerjual) {
            // Ambil riwayat pembelian barang berdasarkan FIFO
            $pembelianBarang = DB::table('detail_pembelians')
                ->select(
                    'detail_pembelians.id',
                    'pembelians.no_ref',
                    'pembelians.tanggal_pembelian as tanggal',
                    'barangs.nama as nama_barang',
                    'detail_pembelians.qty_base',
                    'detail_pembelians.pajak_value',
                    'pajaks.persen as persentase_pajak'
                )
                ->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
                ->join('barangs', 'detail_pembelians.barang_id', '=', 'barangs.id')
                ->join('pajaks', 'barangs.pajak_id', '=', 'pajaks.id')
                ->where('detail_pembelians.barang_id', $barangId)
                ->whereIn('pembelians.status', ['received', 'completed'])
                ->orderBy('pembelians.tanggal_pembelian') // FIFO
                ->get();

            // Jumlah barang terjual yang perlu dihitung
            $sisaJumlahHitung = $jumlahTerjual;

            // Iterasi per detail pembelian untuk barang ini
            foreach ($pembelianBarang as $pembelian) {
                if ($sisaJumlahHitung <= 0) break;

                // Jumlah yang diperhitungkan dari pembelian ini
                $jumlahDihitung = min($pembelian->qty_base, $sisaJumlahHitung);
                $sisaJumlahHitung -= $jumlahDihitung;

                // Hitung nilai pajak sesuai proporsi
                $nilaiPajak = $pembelian->pajak_value * $jumlahDihitung;

                // Buat objek stdClass untuk kompatibilitas dengan view
                $pajakItem = new stdClass();
                $pajakItem->no_ref = $pembelian->no_ref;
                $pajakItem->tanggal = $pembelian->tanggal;
                $pajakItem->nama_barang = $pembelian->nama_barang;
                $pajakItem->persentase_pajak = $pembelian->persentase_pajak;
                $pajakItem->jumlah_barang = $jumlahDihitung;
                $pajakItem->nilai_pajak = $nilaiPajak;

                // Tambahkan ke hasil
                $hasilPajakMasukan->push($pajakItem);
            }
        }

        return $hasilPajakMasukan;
    }

    /**
     * Ambil data pajak keluaran dari penjualan
     * Note: field pajak pada detail_penjualan sudah merupakan nilai total pajak per item
     */
    private function getPajakKeluaran($bulan, $tahun)
    {
        return DB::table('detail_penjualans')
            ->select(
                'penjualans.no_ref',
                'penjualans.created_at as tanggal',
                'detail_penjualans.nama_barang',
                'detail_penjualans.pajak_value as persentase_pajak',
                'detail_penjualans.kuantitas as jumlah_barang',
                'detail_penjualans.pajak as nilai_pajak' // Sudah berupa nilai total pajak
            )
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->whereMonth('penjualans.created_at', $bulan)
            ->whereYear('penjualans.created_at', $tahun)
            ->where('detail_penjualans.pajak', '>', 0)
            ->orderBy('penjualans.created_at')
            ->get();
    }

    /**
     * Ambil data untuk grafik pajak 6 bulan terakhir
     * Memastikan jumlah pajak masukan sesuai dengan barang yang terjual (FIFO)
     */
    private function getGrafikPajak($bulanAkhir, $tahunAkhir)
    {
        $data = [];

        // Hitung 6 bulan terakhir dari bulan dan tahun yang dipilih
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::createFromDate($tahunAkhir, $bulanAkhir, 1)->subMonths($i);
            $bulan = $date->month;
            $tahun = $date->year;
            $labelBulan = $date->format('M Y');

            // Dapatkan barang terjual untuk bulan ini
            $barangTerjual = $this->getBarangTerjual($bulan, $tahun);

            // Hitung total pajak masukan bulan ini (hanya untuk barang yang terjual)
            $totalPajakMasukan = 0;

            // Iterasi untuk setiap barang yang terjual
            foreach ($barangTerjual as $barangId => $jumlahTerjual) {
                // Ambil riwayat pembelian barang berdasarkan FIFO
                $pembelianBarang = DB::table('detail_pembelians')
                    ->select(
                        'detail_pembelians.id',
                        'detail_pembelians.qty_base',
                        'detail_pembelians.pajak_value'
                    )
                    ->join('pembelians', 'detail_pembelians.pembelian_id', '=', 'pembelians.id')
                    ->where('detail_pembelians.barang_id', $barangId)
                    ->whereIn('pembelians.status', ['received', 'completed'])
                    ->orderBy('pembelians.tanggal_pembelian') // FIFO
                    ->get();

                // Jumlah barang terjual yang perlu dihitung
                $sisaJumlahHitung = $jumlahTerjual;

                // Iterasi per detail pembelian untuk barang ini
                foreach ($pembelianBarang as $pembelian) {
                    if ($sisaJumlahHitung <= 0) break;

                    // Jumlah yang diperhitungkan dari pembelian ini
                    $jumlahDihitung = min($pembelian->qty_base, $sisaJumlahHitung);
                    $sisaJumlahHitung -= $jumlahDihitung;

                    // Tambahkan nilai pajak sesuai proporsi
                    $totalPajakMasukan += $pembelian->pajak_value * $jumlahDihitung;
                }
            }

            // Hitung total pajak keluaran bulan ini
            $totalPajakKeluaran = DB::table('detail_penjualans')
                ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
                ->whereMonth('penjualans.created_at', $bulan)
                ->whereYear('penjualans.created_at', $tahun)
                ->sum('detail_penjualans.pajak');

            // Hitung pajak yang harus dibayarkan
            $pajakDibayarkan = $totalPajakKeluaran - $totalPajakMasukan;

            $data[] = [
                'bulan' => $labelBulan,
                'pajak_masukan' => round($totalPajakMasukan),
                'pajak_keluaran' => round($totalPajakKeluaran),
                'pajak_dibayarkan' => round($pajakDibayarkan)
            ];
        }

        return $data;
    }

    /**
     * Ambil ringkasan pajak per kategori barang
     * Mengelompokkan berdasarkan kategori barang untuk penjualan
     */
    private function getRingkasanPajakPerKategori($bulan, $tahun)
    {
        return DB::table('detail_penjualans')
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->join('barangs', 'detail_penjualans.barang_id', '=', 'barangs.id')
            ->join('kategoris', 'barangs.kategori_id', '=', 'kategoris.id')
            ->select(
                'kategoris.nama as kategori',
                DB::raw('SUM(detail_penjualans.pajak) as total_pajak'),
                DB::raw('SUM(detail_penjualans.subtotal) as total_penjualan'),
                DB::raw('SUM(detail_penjualans.kuantitas) as total_kuantitas')
            )
            ->whereMonth('penjualans.created_at', $bulan)
            ->whereYear('penjualans.created_at', $tahun)
            ->groupBy('kategoris.nama')
            ->orderBy('total_pajak', 'desc')
            ->get();
    }

    /**
     * Download atau cetak laporan pajak
     */
    public function cetak(Request $request)
    {
        // Implementasi cetak laporan
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Format periode untuk ditampilkan
        $periode = Carbon::createFromDate($tahun, $bulan, 1)->format('F Y');

        // Dapatkan barang-barang yang terjual pada periode ini
        $barangTerjual = $this->getBarangTerjual($bulan, $tahun);

        // Ambil data pajak
        $pajakMasukan = $this->getPajakMasukan($bulan, $tahun, $barangTerjual);
        $pajakKeluaran = $this->getPajakKeluaran($bulan, $tahun);

        // Hitung total pajak masukan dan keluaran
        $totalPajakMasukan = $pajakMasukan->sum('nilai_pajak');
        $totalPajakKeluaran = $pajakKeluaran->sum('nilai_pajak');

        // Hitung pajak yang harus dibayarkan
        $pajakDibayarkan = $totalPajakKeluaran - $totalPajakMasukan;

        // Data untuk template PDF
        $data = [
            'periode' => $periode,
            'pajakMasukan' => $pajakMasukan,
            'pajakKeluaran' => $pajakKeluaran,
            'totalPajakMasukan' => $totalPajakMasukan,
            'totalPajakKeluaran' => $totalPajakKeluaran,
            'pajakDibayarkan' => $pajakDibayarkan
        ];

        // Contoh: download laporan sebagai PDF
        // return PDF::loadView('laporan.pajak.cetak', $data)->download('laporan-pajak-' . $periode . '.pdf');

        // Atau, jika PDF belum diimplementasikan, arahkan kembali dengan pesan
        return redirect()->route('laporan.pajak.index', ['bulan' => $bulan, 'tahun' => $tahun])
            ->with('success', 'Laporan pajak berhasil dicetak.');
    }

    /**
     * Generate laporan pajak dalam format PDF
     * Menggunakan package barryvdh/laravel-dompdf untuk konversi view ke PDF
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {
        // Ambil parameter bulan dan tahun dari request atau gunakan bulan dan tahun saat ini
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Format periode untuk ditampilkan
        $periode = Carbon::createFromDate($tahun, $bulan, 1)->format('F Y');

        // Dapatkan barang-barang yang terjual pada periode ini
        $barangTerjual = $this->getBarangTerjual($bulan, $tahun);

        // Ambil data pajak
        $pajakMasukan = $this->getPajakMasukan($bulan, $tahun, $barangTerjual);
        $pajakKeluaran = $this->getPajakKeluaran($bulan, $tahun);

        // Hitung total pajak masukan dan keluaran
        $totalPajakMasukan = $pajakMasukan->sum('nilai_pajak');
        $totalPajakKeluaran = $pajakKeluaran->sum('nilai_pajak');

        // Hitung pajak yang harus dibayarkan
        $pajakDibayarkan = $totalPajakKeluaran - $totalPajakMasukan;

        // Ringkasan pajak per kategori barang
        $ringkasanPajakPerKategori = $this->getRingkasanPajakPerKategori($bulan, $tahun);

        // Ambil data pengaturan toko dari database
        $settingQuery = Setting::whereIn('key', ['toko_nama', 'toko_alamat', 'toko_kota', 'toko_provinsi', 'toko_telepon', 'toko_email', 'toko_npwp'])
            ->pluck('value', 'key')
            ->toArray();


        // Jika data toko tidak ditemukan, gunakan default

        $toko = [
            'nama' => $settingQuery['toko_nama'] ?? env('TOKO_NAMA', 'Nama Toko'),
            'alamat' => $settingQuery['toko_alamat'] ?? env('TOKO_ALAMAT', 'Alamat Toko'),
            'kota' => $settingQuery['toko_kota'] ?? env('TOKO_KOTA', 'Jakarta'),
            'provinsi' => $settingQuery['toko_provinsi'] ?? env('TOKO_PROVINSI', 'DKI Jakarta'),
            'telepon' => $settingQuery['toko_telepon'] ?? env('TOKO_TELEPON', 'Telepon Toko'),
            'email' => $settingQuery['toko_email'] ?? env('TOKO_EMAIL', 'Email Toko'),
            'npwp' => $settingQuery['toko_npwp'] ?? env('TOKO_NPWP', 'NPWP Toko'),
        ];


        // Data untuk template PDF
        $data = [
            'toko' => $toko,
            'periode' => $periode,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pajakMasukan' => $pajakMasukan,
            'pajakKeluaran' => $pajakKeluaran,
            'totalPajakMasukan' => $totalPajakMasukan,
            'totalPajakKeluaran' => $totalPajakKeluaran,
            'pajakDibayarkan' => $pajakDibayarkan,
            'ringkasanPajakPerKategori' => $ringkasanPajakPerKategori,
            'tanggal_cetak' => Carbon::now()->format('d F Y H:i:s')
        ];

        // Generate PDF menggunakan package barryvdh/laravel-dompdf
        $pdf = PDF::loadView('laporan.keuangan.pdf.pajak-pdf', $data);

        // Set opsi PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Download PDF dengan nama yang sesuai periode
        $namaPDF = 'laporan-pajak-' . Carbon::createFromDate($tahun, $bulan, 1)->format('Y-m') . '.pdf';
        return $pdf->stream($namaPDF);
    }
}
