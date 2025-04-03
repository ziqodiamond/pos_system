<?php

namespace App\Http\Controllers\Pembelian;

use App\Models\Pajak;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Setting;
use App\Models\Konversi;
use App\Models\Supplier;
use Illuminate\Http\Request;
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
        dd($request->all());
        // Validasi input
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'no_faktur' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'no_referensi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'lunas' => 'boolean',
            'biaya_lain' => 'nullable|numeric|min:0',
            'diskon' => 'nullable|numeric|min:0',
            'diskon_type' => 'required|in:persen,nominal',
            'total_diskon' => 'nullable|numeric|min:0',
            'total_pajak' => 'nullable|numeric|min:0',
            'uang_muka' => 'nullable|numeric|min:0',
            'grand_total' => 'required|numeric|min:0',
            'sisa' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.jumlah' => 'required|numeric|min:0.01',
            'items.*.satuan_id' => 'required|exists:satuans,id',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
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
            $pembelian->subtotal = $request->subtotal;
            $pembelian->diskon_mode = $validatedData['diskon_type'];
            $pembelian->diskon_value = $validatedData['diskon'] ?? 0;
            $pembelian->pajak_id = null; // Tidak ada pajak pada level pembelian sesuai model
            $pembelian->pajak_value = $validatedData['total_pajak'] ?? 0;
            $pembelian->biaya_lainnya = $validatedData['biaya_lain'] ?? 0;
            $pembelian->total = $validatedData['grand_total'];
            $pembelian->save();

            // Menyimpan detail pembelian untuk setiap item
            foreach ($validatedData['items'] as $itemData) {
                // Mengambil data barang beserta data pajak yang terelasi
                $barang = Barang::with('pajak')->find($itemData['barang_id']);

                // Inisialisasi variabel pajak
                $pajak_id = null;
                $pajak_persen = 0;
                $pajak_value = 0;

                // Jika barang memiliki pajak yang terkait
                if ($barang && $barang->pajak) {
                    $pajak_id = $barang->pajak->id;
                    $pajak_persen = $barang->pajak->nilai; // Nilai pajak dalam persen

                    // Menghitung nilai pajak per item (harga satuan * persentase pajak)
                    $pajak_value = $itemData['harga_satuan'] * ($pajak_persen / 100) * $itemData['jumlah'];
                }

                $detailPembelian = new DetailPembelian();
                $detailPembelian->pembelian_id = $pembelian->id;
                $detailPembelian->barang_id = $itemData['barang_id'];
                $detailPembelian->kuantitas = $itemData['jumlah'];
                $detailPembelian->satuan_id = $itemData['satuan_id'];
                $detailPembelian->harga_satuan = $itemData['harga_satuan'];
                $detailPembelian->subtotal = $itemData['total'];
                $detailPembelian->pajak_id = $pajak_id; // ID pajak dari barang
                $detailPembelian->pajak_value = $pajak_value; // Nilai pajak yang dihitung
                $detailPembelian->stok = $itemData['jumlah']; // Stok awal sama dengan jumlah pembelian
                $detailPembelian->save();

                // Update stok barang
                if ($barang) {
                    $barang->stok = $barang->stok + $itemData['jumlah'];
                    $barang->save();
                }
            }

            // Buat faktur pembelian untuk semua kasus (baik lunas maupun tidak)
            $fakturPembelian = new FakturPembelian();
            $fakturPembelian->supplier_id = $validatedData['supplier_id']; // Supplier ID dari validasi
            $fakturPembelian->no_faktur = $validatedData['no_faktur'];
            $fakturPembelian->tanggal_faktur = $validatedData['tanggal'];
            $fakturPembelian->deskripsi = $validatedData['deskripsi'] ?? null;
            $fakturPembelian->subtotal = $request->subtotal;
            $fakturPembelian->biaya_lainnya = $validatedData['biaya_lain'] ?? 0;
            $fakturPembelian->diskon_mode = $validatedData['diskon_type'];
            $fakturPembelian->diskon_value = $validatedData['diskon'] ?? 0;
            $fakturPembelian->pajak_id = null; // Sesuai dengan yang di Pembelian
            $fakturPembelian->pajak_value = $validatedData['total_pajak'] ?? 0;
            $fakturPembelian->total_tagihan = $validatedData['grand_total']; // Total dari pembelian

            // Inisialisasi status dan total pembayaran
            $total_bayar = 0;
            $status = 'unpaid';

            // Jika lunas
            if ($request->lunas) {
                $total_bayar = $validatedData['grand_total']; // Total bayar sama dengan grand total
                $status = 'paid';
            }
            // Jika tidak lunas tapi ada uang muka
            else if (isset($validatedData['uang_muka']) && $validatedData['uang_muka'] > 0) {
                $total_bayar = $validatedData['uang_muka'];
                $status = ($total_bayar >= $fakturPembelian->total_tagihan) ? 'paid' : 'partial';
            }

            $fakturPembelian->total_bayar = $total_bayar; // Uang muka atau pembayaran penuh jika lunas
            $fakturPembelian->total_hutang = $validatedData['grand_total'] - $total_bayar; // Sisa hutang
            $fakturPembelian->status = $status;
            $fakturPembelian->save();

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            return redirect()->route('pembelian.index')
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
