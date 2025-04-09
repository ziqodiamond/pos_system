<?php

namespace App\Http\Controllers\Inventori;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Konversi;
use App\Models\BarangKeluar;
use App\Models\DetailPembelian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarangKeluarController extends Controller
{
    public function index()
    {
        // Ambil semua data barang dengan relasi satuan
        $barangs = Barang::with('satuan')->where('stok', '>', 0)->get();

        // Ambil semua data satuan
        $satuans = Satuan::all();

        // Ambil semua data konversi satuan
        $konversis = Konversi::all();

        return view('inventori.barang-keluar.index', compact('barangs', 'satuans', 'konversis'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_barang' => 'required|string',
            'satuan_id' => 'required|exists:satuans,id',
            'kuantitas' => 'required|numeric|min:1',
            'tanggal_keluar' => 'required|date',
            'jenis' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        try {
            // Mulai transaksi database untuk memastikan integritas data
            DB::beginTransaction();

            // Ambil data barang yang akan dikeluarkan
            $barang = Barang::findOrFail($request->barang_id);

            // Periksa apakah stok mencukupi
            if ($barang->stok < $request->kuantitas) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['kuantitas' => 'Stok tidak mencukupi. Stok saat ini: ' . $barang->stok]);
            }

            // Ambil detail pembelian dengan metode FIFO (terlama dulu)
            // yang masih memiliki stok
            $detailPembelians = DetailPembelian::where('barang_id', $request->barang_id)
                ->where('stok', '>', 0)
                ->orderBy('created_at', 'asc')
                ->get();

            // Jika tidak ada detail pembelian dengan stok, kembalikan error
            if ($detailPembelians->isEmpty()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['barang_id' => 'Tidak ada stok tersedia untuk barang ini']);
            }

            // Hitung total kuantitas yang akan dikeluarkan
            $sisaKuantitas = $request->kuantitas;
            $totalHargaPokok = 0;

            // Kumpulkan data untuk perhitungan rata-rata harga pokok
            $detailPengurangan = [];

            // Proses pengurangan stok dengan metode FIFO
            foreach ($detailPembelians as $detail) {
                // Jika sisa kuantitas sudah 0, selesai
                if ($sisaKuantitas <= 0) break;

                // Hitung berapa banyak yang akan diambil dari detail pembelian ini
                $jumlahDiambil = min($sisaKuantitas, $detail->stok);

                // Simpan data pengurangan untuk pembaruan nanti
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
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['kuantitas' => 'Stok tidak mencukupi untuk transaksi ini']);
            }

            // Hitung rata-rata harga pokok berdasarkan FIFO
            $hargaSatuan = $totalHargaPokok / $request->kuantitas;

            // Hitung subtotal dari harga satuan dan kuantitas
            $subtotal = $hargaSatuan * $request->kuantitas;

            // Buat record barang keluar
            $barangKeluar = BarangKeluar::create([
                'user_id' => Auth::id(), // ID user yang login
                'barang_id' => $request->barang_id,
                'nama_barang' => $request->nama_barang,
                'kuantitas' => $request->kuantitas,
                'harga_satuan' => $hargaSatuan, // Gunakan harga pokok dari detail pembelian
                'subtotal' => $subtotal, // Tambahkan subtotal yang dihitung
                'satuan_id' => $request->satuan_id,
                'jenis' => $request->jenis,
                'keterangan' => $request->keterangan,
                'tanggal_keluar' => $request->tanggal_keluar,
            ]);

            // Update stok di setiap detail pembelian yang digunakan
            foreach ($detailPengurangan as $item) {
                $detailPembelian = DetailPembelian::find($item['id']);
                $detailPembelian->stok -= $item['jumlah_diambil'];
                $detailPembelian->save();
            }

            // Update stok di tabel barang
            $barang->stok -= $request->kuantitas;
            $barang->save();

            // Commit transaksi database
            DB::commit();

            return redirect()->back()
                ->with('success', 'Data barang keluar berhasil disimpan');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
