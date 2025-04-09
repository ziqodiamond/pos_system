<?php

namespace App\Http\Controllers\Penjualan;

use Carbon\Carbon;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Kategori;
use App\Models\Penjualan;
use Illuminate\Support\Str;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class TransaksiController extends Controller
{
    public function index()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        $barangs = Barang::with(['kategori', 'satuan', 'pajak'])->get();
        $customers = Customer::all();
        $kategoris = Kategori::all();
        return view('penjualan.transaksi.index', compact('breadcrumbs', 'barangs', 'customers', 'kategoris'));
    }


    /**
     * Menyimpan data penjualan baru ke database
     * Fungsi ini menangani request Ajax untuk transaksi POS
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('Mulai proses store penjualan', ['request' => $request->all()]);
        // Validasi request
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'metode_pembayaran' => 'required|in:tunai,kartu,transfer,qris',
            'total_bayar' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'total_diskon' => 'required|numeric|min:0',
            'total_pajak' => 'required|numeric|min:0',
            'dpp' => 'required|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.nama_barang' => 'required|string',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.kuantitas' => 'required|numeric|min:1',
            'items.*.satuan_id' => 'required|exists:satuans,id',
        ]);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Buat record Penjualan baru
            $penjualan = new Penjualan();
            $penjualan->no_ref = 'INV-' . date('ymd') . '-' . str_pad(Penjualan::count() + 1, 8, '0', STR_PAD_LEFT);
            $penjualan->kasir_id = auth()->id(); // Menggunakan ID user yang sedang login sebagai kasir
            $penjualan->customer_id = $request->customer_id;
            $penjualan->subtotal = $request->subtotal;
            $penjualan->total_diskon = $request->total_diskon;
            $penjualan->total_pajak = $request->total_pajak;
            $penjualan->dpp = $request->dpp;
            $penjualan->grand_total = $request->grand_total;
            $penjualan->total_bayar = $request->total_bayar;
            $penjualan->kembalian = $request->kembalian;
            $penjualan->metode_pembayaran = $request->metode_pembayaran;
            $penjualan->save();

            Log::info('Penjualan berhasil dibuat', ['penjualan_id' => $penjualan->id]);

            // Memproses setiap item dalam transaksi
            foreach ($request->items as $item) {
                Log::info('Proses item', $item);
                // Simpan detail penjualan
                $detailPenjualan = new DetailPenjualan();
                $detailPenjualan->penjualan_id = $penjualan->id;
                $detailPenjualan->barang_id = $item['barang_id'];
                $detailPenjualan->nama_barang = $item['nama_barang'];
                $detailPenjualan->harga_satuan = $item['harga_dengan_pajak']; // Harga sudah termasuk pajak
                $detailPenjualan->harga_diskon = $item['harga_setelah_diskon'];
                $detailPenjualan->pajak_value = $item['pajak_value'];
                $detailPenjualan->diskon_value = $item['diskon_value'];
                $detailPenjualan->diskon_nominal = $item['diskon_nominal_dasar'];
                $detailPenjualan->kuantitas = $item['kuantitas'];
                $detailPenjualan->satuan_id = $item['satuan_id'];
                $detailPenjualan->total_diskon = $item['total_diskon'];
                $detailPenjualan->pajak = $item['total_pajak'];
                $detailPenjualan->subtotal = $item['subtotal'];
                $detailPenjualan->total = $item['total'];
                $detailPenjualan->save();

                // Hitung harga pokok rata-rata FIFO untuk barang keluar
                $hargaPokokInfo = $this->hitungHargaPokokFIFO($item['barang_id'], $item['kuantitas']);
                $hargaSatuan = $hargaPokokInfo['harga_satuan'];
                $subtotal = $hargaPokokInfo['subtotal'];

                // Simpan barang keluar dengan harga_satuan dan subtotal dari FIFO
                $barangKeluar = new BarangKeluar();
                $barangKeluar->barang_id = $item['barang_id'];
                $barangKeluar->user_id = auth()->id(); // Menggunakan ID user yang sedang login
                $barangKeluar->nama_barang = $item['nama_barang'];
                $barangKeluar->kuantitas = $item['kuantitas'];
                $barangKeluar->satuan_id = $item['satuan_id'];
                $barangKeluar->harga_satuan = $hargaSatuan; // Menggunakan harga pokok dari FIFO
                $barangKeluar->subtotal = $subtotal; // Menggunakan subtotal dari FIFO
                $barangKeluar->jenis = 'penjualan';
                $barangKeluar->keterangan = 'Penjualan No. ' . $penjualan->no_ref; // Menggunakan no_ref yang dihasilkan
                $barangKeluar->tanggal_keluar = Carbon::now()->format('Y-m-d');
                $barangKeluar->save();

                Log::info('Barang keluar disimpan', [
                    'barang_keluar_id' => $barangKeluar->id,
                    'harga_satuan_fifo' => $hargaSatuan,
                    'subtotal_fifo' => $subtotal
                ]);

                // Update stok barang (FIFO)
                $this->updateStokBarangFIFO($item['barang_id'], $item['kuantitas']);
            }

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            Log::info('Transaksi berhasil disimpan', ['penjualan_id' => $penjualan->id]);
            // Mengembalikan pengguna ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            Log::error('Gagal menyimpan transaksi', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Mengembalikan pengguna ke halaman sebelumnya dengan pesan error
            return redirect()->back()->with('error', 'Transaksi gagal disimpan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hitung harga pokok berdasarkan metode FIFO untuk BarangKeluar
     * 
     * @param string $barangId
     * @param int $kuantitas
     * @return array
     */
    private function hitungHargaPokokFIFO($barangId, $kuantitas)
    {
        Log::debug('Memulai perhitungan harga pokok FIFO', [
            'barang_id' => $barangId,
            'kuantitas' => $kuantitas
        ]);

        // Ambil detail pembelian yang masih memiliki stok, urutkan dari yang terlama
        $detailPembelians = DetailPembelian::where('barang_id', $barangId)
            ->where('stok', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        $sisaKuantitas = $kuantitas;
        $totalHargaPokok = 0;
        $detailPengurangan = [];

        // Hitung total harga pokok berdasarkan FIFO
        foreach ($detailPembelians as $detail) {
            // Jika sisa kuantitas sudah 0, selesai
            if ($sisaKuantitas <= 0) break;

            // Hitung berapa banyak yang akan diambil dari detail pembelian ini
            $jumlahDiambil = min($sisaKuantitas, $detail->stok);

            // Simpan data pengurangan untuk referensi
            $detailPengurangan[] = [
                'id' => $detail->id,
                'jumlah_diambil' => $jumlahDiambil,
                'harga_pokok' => $detail->harga_pokok
            ];

            // Akumulasi total harga pokok
            $totalHargaPokok += $jumlahDiambil * $detail->harga_pokok;

            // Kurangi sisa kuantitas
            $sisaKuantitas -= $jumlahDiambil;
        }

        // Jika sisa kuantitas masih ada, berarti stok tidak cukup
        if ($sisaKuantitas > 0) {
            Log::error("Stok barang tidak mencukupi untuk hitung harga pokok", [
                'barang_id' => $barangId,
                'sisa_kuantitas_yang_belum_terpenuhi' => $sisaKuantitas
            ]);
            throw new \Exception("Stok barang dengan ID {$barangId} tidak mencukupi");
        }

        // Hitung rata-rata harga pokok berdasarkan FIFO
        $hargaSatuan = $totalHargaPokok / $kuantitas;
        $subtotal = $totalHargaPokok;

        Log::debug('Hasil perhitungan harga pokok FIFO', [
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
            'detail_pengurangan' => $detailPengurangan
        ]);

        return [
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
            'detail_pengurangan' => $detailPengurangan
        ];
    }

    /**
     * Update stok barang menggunakan metode FIFO
     * Mengambil stok dari pembelian terlama yang masih memiliki stok
     * 
     * @param string $barangId
     * @param int $kuantitasKeluar
     * @return void
     */
    private function updateStokBarangFIFO($barangId, $kuantitasKeluar)
    {
        Log::debug('Memulai proses update stok FIFO', [
            'barang_id' => $barangId,
            'kuantitas_keluar' => $kuantitasKeluar
        ]);

        // Ambil data barang
        $barang = Barang::findOrFail($barangId);
        Log::debug('Data barang ditemukan', ['barang' => $barang->toArray()]);

        // Kurangi stok barang di model Barang
        $barang->stok -= $kuantitasKeluar;
        $barang->save();
        Log::debug('Stok barang di model Barang dikurangi', ['stok_tersisa' => $barang->stok]);

        // Sisa kuantitas yang harus diambil
        $sisaKuantitas = $kuantitasKeluar;

        // Ambil detail pembelian yang masih memiliki stok, urutkan dari yang terlama
        $detailPembelians = DetailPembelian::where('barang_id', $barangId)
            ->where('stok', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();
        Log::debug('Detail pembelian yang ditemukan', ['jumlah' => $detailPembelians->count()]);

        // Loop untuk pengurangan stok dari setiap record detail pembelian
        foreach ($detailPembelians as $detail) {
            Log::debug('Memproses detail pembelian', [
                'detail_id' => $detail->id,
                'sisa_stok_sebelum' => $detail->stok,
                'sisa_kuantitas_yang_dibutuhkan' => $sisaKuantitas,
            ]);
            // Hitung berapa banyak stok yang bisa diambil dari detail pembelian ini
            $stokDiambil = min($sisaKuantitas, $detail->stok);

            // Update sisa stok di detail pembelian
            $detail->stok -= $stokDiambil;
            $detail->save();

            Log::debug('Sisa stok di detail pembelian setelah dikurangi', [
                'detail_id' => $detail->id,
                'stok_diambil' => $stokDiambil,
                'sisa_stok_setelah' => $detail->stok,
            ]);

            // Kurangi sisa kuantitas yang perlu diambil
            $sisaKuantitas -= $stokDiambil;

            // Jika semua kuantitas sudah diambil, keluar dari loop
            if ($sisaKuantitas <= 0) {
                break;
            }
        }

        // Jika masih ada sisa kuantitas yang perlu diambil (stok tidak cukup)
        if ($sisaKuantitas > 0) {
            Log::error("Stok barang tidak mencukupi", [
                'barang' => $barang->nama,
                'sisa_kuantitas_yang_belum_terpenuhi' => $sisaKuantitas
            ]);
            throw new \Exception("Stok barang {$barang->nama} tidak mencukupi");
        }
    }
}
