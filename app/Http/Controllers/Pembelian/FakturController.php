<?php

namespace App\Http\Controllers\Pembelian;

use Illuminate\Http\Request;
use App\Models\FakturPembelian;
use App\Models\PembayaranFaktur;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class FakturController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        $query = FakturPembelian::with([
            'supplier',
            'pembelian' => function ($query) {
                $query->with('details', 'user', 'supplier');
            },
        ]);

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nomor_faktur) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(supplier) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(user) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(user) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter status
        if ($request->has('Status') && in_array($request->status, ['processing', 'completed', 'canceled'])) {
            $query->where('status', $request->status);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Paginate hasil
        $faktur = $query->paginate(20);

        return view('pembelian.faktur-pembelian.index', compact('breadcrumbs', 'faktur'));
    }

    public function bayar(Request $request, $id)
    {
        try {
            // Validasi request
            $request->validate([
                'nominal' => 'required|string', // Beri tahu bahwa nominal adalah string format rupiah
                'metode_pembayaran' => 'required|string',
                'deskripsi' => 'nullable|string|max:255'
            ]);

            // Log request masuk
            \Log::info('Payment Request:', $request->all());

            // Konversi input pembayaran dari format rupiah ke integer
            // Penjelasan: Input dalam format "Rp 1.000.000,00" dikonversi jadi integer sen
            $bayar = (int) str_replace(['.', 'Rp ', ',00'], '', $request->nominal);

            // Asumsi input sudah dalam rupiah bulat, jadi kali 100 untuk jadi sen
            $bayar = $bayar * 100;

            \Log::info('Converted payment amount:', ['bayar' => $bayar]);

            // Ambil faktur
            $faktur = FakturPembelian::findOrFail($id);
            \Log::info('Found invoice:', ['faktur_id' => $id, 'total_hutang' => $faktur->total_hutang]);

            // Logika pembulatan hutang ke rupiah terdekat
            $total_hutang_rupiah = $faktur->total_hutang / 100;  // Konversi sen ke rupiah
            $total_hutang_bulat = ceil($total_hutang_rupiah);    // Bulatkan ke atas ke rupiah terdekat
            $total_hutang_bulat_sen = $total_hutang_bulat * 100; // Konversi kembali ke sen

            \Log::info('Debt calculations:', [
                'total_hutang_sen' => $faktur->total_hutang,
                'total_hutang_rupiah' => $total_hutang_rupiah,
                'total_hutang_bulat' => $total_hutang_bulat,
                'total_hutang_bulat_sen' => $total_hutang_bulat_sen
            ]);

            // Hitung sisa hutang
            $sisa_hutang = $total_hutang_bulat_sen - $bayar;
            \Log::info('Remaining debt:', ['sisa_hutang' => $sisa_hutang]);

            // Validasi jumlah pembayaran tidak melebihi total hutang
            if ($sisa_hutang < 0) {
                \Log::error('Payment exceeds total debt', [
                    'payment' => $bayar,
                    'total_debt' => $total_hutang_bulat_sen
                ]);
                throw new \Exception('Jumlah pembayaran melebihi total hutang');
            }

            // Mulai transaksi database untuk memastikan konsistensi data
            \DB::beginTransaction();

            try {
                // Update faktur dengan sisa hutang baru
                $faktur->update([
                    'total_hutang' => $sisa_hutang
                ]);

                \Log::info('Invoice updated', ['faktur_id' => $id, 'new_total_hutang' => $sisa_hutang]);

                // Buat record pembayaran
                $payment = PembayaranFaktur::create([
                    'faktur_id' => $id,
                    'tanggal_pembayaran' => now(),
                    'jumlah_pembayaran' => $bayar,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'deskripsi' => $request->deskripsi ?? null,
                ]);

                \Log::info('Payment record created:', ['payment_id' => $payment->id]);

                \DB::commit();

                return redirect()->back()->with('success', 'Pembayaran berhasil disimpan');
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Payment processing error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }
}
