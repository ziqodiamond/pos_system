<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Pajak;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Setting;
use App\Models\Konversi;
use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use App\Models\DetailPembelian;
use App\Models\FakturPembelian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class PembelianController extends Controller
{
    public function index()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        return view('pembelian.index', compact('breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        $barangs = Barang::all();
        $suppliers = Supplier::all();
        $satuans = Satuan::all();
        $konversiSatuans = Konversi::all();
        $konversis = Konversi::all();
        $pajaks = Pajak::all();
        $settings = Setting::all();
        return view('pembelian.form_pembelian.index', compact('breadcrumbs', 'barangs', 'suppliers', 'pajaks', 'satuans', 'settings', 'konversis', 'konversiSatuans'));
    }

    /**
     * Menyimpan data pembelian baru ke database
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'no_faktur' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'no_referensi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lunas' => 'boolean',
            'include_pajak' => 'boolean',
            'subtotalBeforeDiscount' => 'required|numeric|min:0',
            'subtotalAfterDiscount' => 'required|numeric|min:0',
            'biaya_lain' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'diskon_mode' => 'required|in:persen,nominal',
            'total_diskon' => 'nullable|numeric|min:0',
            'total_pajak' => 'nullable|numeric|min:0',
            'biaya_lain' => 'nullable|numeric|min:0',
            'uang_muka' => 'required|numeric|min:0', // Diubah menjadi required
            'grand_total' => 'required|numeric|min:0',
            'sisa' => 'required|numeric|min:0', // Diubah menjadi required
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.jumlah_efektif' => 'required|numeric|min:1',
            'items.*.satuan_id' => 'required|exists:satuans,id',
            'items.*.satuan_dasar_id' => 'required|exists:satuans,id',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.harga_diskon' => 'required|numeric|min:0',
            'items.*.harga_pokok' => 'required|numeric|min:0',
            'items.*.other_cost' => 'required|numeric|min:0',
            'items.*.diskon_value' => 'required|numeric|min:0',
            'items.*.pajak_value' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'items.*.pajak_id' => 'required|exists:pajaks,id', // Memperbaiki typo 'ecists' menjadi 'exists'
        ], [
            // Pesan error custom untuk validasi
            'uang_muka.required' => 'Uang muka harus diisi',
            'sisa.required' => 'Sisa pembayaran harus diisi',
            'items.required' => 'Minimal satu item barang harus diisi',
            'items.*.barang_id.required' => 'ID barang harus diisi',
            'items.*.barang_id.exists' => 'Barang tidak ditemukan',
            'items.*.jumlah.required' => 'Jumlah barang harus diisi',
            'items.*.jumlah.min' => 'Jumlah barang minimal 1',
            'items.*.jumlah_efektif.required' => 'Jumlah efektif barang harus diisi',
            'items.*.jumlah_efektif.min' => 'Jumlah efektif barang minimal 1',
            'items.*.satuan_id.required' => 'Satuan barang harus diisi',
            'items.*.satuan_id.exists' => 'Satuan barang tidak ditemukan',
            'items.*.satuan_dasar_id.required' => 'Satuan dasar barang harus diisi',
            'items.*.satuan_dasar_id.exists' => 'Satuan dasar barang tidak ditemukan',
            'items.*.pajak_id.exists' => 'Pajak tidak ditemukan',
        ]);

        // Memulai transaksi database untuk memastikan integritas data
        DB::beginTransaction();

        try {
            // Membuat objek pembelian baru
            $pembelian = new Pembelian();
            $pembelian->no_ref = $validatedData['no_referensi'];
            $pembelian->tanggal_pembelian = $validatedData['tanggal'];
            $pembelian->tanggal_masuk = null; // Tanggal masuk di null kan dulu sesuai permintaan
            $pembelian->supplier_id = $validatedData['supplier_id'];
            $pembelian->user_id = auth()->id(); // ID user yang sedang login
            $pembelian->deskripsi = $validatedData['deskripsi'] ?? null;
            $pembelian->subtotal = $validatedData['subtotalBeforeDiscount'];
            $pembelian->diskon_mode = $validatedData['diskon_mode'];
            $pembelian->diskon_value = $validatedData['total_diskon'] ?? 0;
            $pembelian->pajak_value = $validatedData['total_pajak'] ?? 0;
            $pembelian->biaya_lainnya = $validatedData['biaya_lain'] ?? 0;
            $pembelian->total = $validatedData['grand_total'];
            $pembelian->status = 'processing'; // Status awal adalah processing
            $pembelian->save();

            // Menyimpan detail pembelian untuk setiap item
            foreach ($validatedData['items'] as $itemData) {
                // Ambil data barang terlebih dahulu
                $barang = Barang::findOrFail($itemData['barang_id']);

                $detailPembelian = new DetailPembelian();
                $detailPembelian->pembelian_id = $pembelian->id;
                $detailPembelian->barang_id = $itemData['barang_id'];
                $detailPembelian->qty_user = $itemData['jumlah'];
                $detailPembelian->qty_base = $itemData['jumlah_efektif'];
                $detailPembelian->satuan_id = $itemData['satuan_id'];
                $detailPembelian->satuan_dasar_id = $itemData['satuan_dasar_id'];
                $detailPembelian->harga_satuan = $itemData['harga_satuan']; //tanpa pajak
                $detailPembelian->harga_diskon = $itemData['harga_diskon']; //tanpa pajak
                $detailPembelian->harga_pokok = $itemData['harga_pokok']; // harga pokok barang (harga_diskon + other_cost + pajak_value)   
                $detailPembelian->other_cost = $itemData['other_cost'];
                $detailPembelian->diskon_value = $itemData['diskon_value']; //diskon satuan
                $detailPembelian->pajak_value = $itemData['pajak_value']; //pajak satuan
                $detailPembelian->pajak_id = $itemData['pajak_id']; // ID pajak dari barang
                $detailPembelian->subtotal = $itemData['subtotal']; //tanpa pajak, diskon. n other cost (harga_satuan*jumlah efektif)
                $detailPembelian->total = $itemData['total']; // dengan diskon, pajak, other cost (harga_pokok*jumlah efektif)
                $detailPembelian->stok = $itemData['jumlah_efektif']; // Stok awal sama dengan jumlah pembelian
                $detailPembelian->save();

                // Update stok barang
                // Memastikan model barang ada sebelum update stok
                if ($barang) {
                    $barang->stok = $barang->stok + $itemData['jumlah_efektif'];
                    $barang->save();
                }
            }

            // Buat faktur pembelian untuk semua kasus (baik lunas maupun tidak)
            $fakturPembelian = new FakturPembelian();
            $fakturPembelian->pembelian_id = $pembelian->id;
            $fakturPembelian->supplier_id = $validatedData['supplier_id']; // Supplier ID dari validasi
            $fakturPembelian->no_faktur = $validatedData['no_faktur'];
            $fakturPembelian->tanggal_faktur = $validatedData['tanggal'];
            $fakturPembelian->deskripsi = $validatedData['deskripsi'] ?? null;
            $fakturPembelian->subtotal = $validatedData['subtotalBeforeDiscount'];
            $fakturPembelian->biaya_lainnya = $validatedData['biaya_lain'] ?? 0;
            $fakturPembelian->diskon_mode = $validatedData['diskon_mode'];
            $fakturPembelian->diskon_value = $validatedData['total_diskon'] ?? 0;
            $fakturPembelian->pajak_value = $validatedData['total_pajak'] ?? 0;
            $fakturPembelian->total_tagihan = $validatedData['grand_total']; // Total dari pembelian;

            if ($validatedData['lunas']) {
                $fakturPembelian->total_bayar = $validatedData['grand_total']; // Total bayar sama dengan grand total
                $fakturPembelian->total_hutang = 0; // Tidak ada sisa hutang
                $fakturPembelian->status = 'lunas';
            } else {
                $fakturPembelian->total_bayar = $validatedData['uang_muka']; // Uang muka atau pembayaran penuh jika lunas
                $fakturPembelian->total_hutang = $validatedData['sisa']; // Sisa hutang
                $fakturPembelian->status = 'hutang';
            }

            $fakturPembelian->save();

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect()->back()
                ->with('success', 'Pembelian berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollback();

            // Log error untuk debugging
            Log::error('Error saat menyimpan pembelian: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan pembelian: ' . $e->getMessage());
        }
    }

    public function list()
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();
        return view('pembelian.daftar_pembelian.index', compact('breadcrumbs'));
    }
}
