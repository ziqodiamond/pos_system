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
            // Validate request
            $request->validate([
                'nominal' => 'required',
                'deskripsi' => 'nullable|string|max:255'
            ]);

            // Convert input payment from rupiah format to integer       
            $bayar = (int) str_replace(['.', 'Rp ', ',00'], '', $request->nominal) * 100;

            // Get faktur
            $faktur = FakturPembelian::findOrFail($id);

            // Convert total hutang to rupiah, round up, then back to sen
            $total_hutang_rupiah = $faktur->total_hutang / 100;
            $total_hutang_bulat = ceil($total_hutang_rupiah);
            $total_hutang_bulat_sen = $total_hutang_bulat * 100;

            // Calculate remaining debt
            $sisa_hutang = $total_hutang_bulat_sen - $bayar;

            if ($sisa_hutang < 0) {
                throw new \Exception('Jumlah pembayaran melebihi total hutang');
            }

            // Update faktur
            $faktur->update([
                'total_hutang' => $sisa_hutang
            ]);

            // Create payment record
            PembayaranFaktur::create([
                'faktur_id' => $id,
                'tanggal_pembayaran' => now(),
                'jumlah_pembayaran' => $bayar,
                'deskripsi' => $request->deskripsi ?? null,
            ]);

            return redirect()->back()->with('success', 'Pembayaran berhasil disimpan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pembayaran: ' . $e->getMessage())->withInput();
        }
    }
}
