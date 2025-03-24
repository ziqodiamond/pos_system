<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\BreadcrumbController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar customer
        $query = Customer::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter status
        if ($request->has('Status') && in_array($request->status, ['aktif', 'nonaktif'])) {
            $query->where('status', $request->status);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        // Paginate hasil
        $customers = $query->paginate(10);

        // Cek apakah ini request AJAX (buat partial render)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.customer.partials.table', compact('customers'))->render(),
                'pagination' => $customers->links()->toHtml(),
            ]);
        }

        return view('master_data.customer.index', compact('customers', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required|email|unique:customers',
            'tanggal_lahir' => 'required|date',
        ]);

        // Generate customer code with format CST-YYYYMM-XXXX
        $latestCustomer = Customer::orderBy('created_at', 'desc')->first();
        $currentDate = now()->format('Ym');

        if ($latestCustomer && str_contains($latestCustomer->kode, $currentDate)) {
            $lastNumber = (int) substr($latestCustomer->kode, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $customer = new Customer();
        $customer->kode = "CST-{$currentDate}-{$newNumber}";
        $customer->nama = $request->nama;
        $customer->alamat = $request->alamat;
        $customer->telepon = $request->telepon;
        $customer->email = $request->email;
        $customer->tanggal_lahir = $request->tanggal_lahir;
        $customer->status = $request->has('status') ? 'active' : 'inactive';
        $customer->save();

        return redirect()->back()->with('success', 'Customer berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:customers,kode,' . $id,
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required|email|unique:customers,email,' . $id,
            'tanggal_lahir' => 'required|date',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->kode = $request->kode;
        $customer->nama = $request->nama;
        $customer->alamat = $request->alamat;
        $customer->telepon = $request->telepon;
        $customer->email = $request->email;
        $customer->tanggal_lahir = $request->tanggal_lahir;
        $customer->status = $request->status;
        $customer->save();

        return redirect()->back()->with('success', 'Customer berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return redirect()->back()->with('success', 'Customer berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $customer = Customer::withTrashed()->findOrFail($id);
            $customer->restore();

            return redirect()->back()->with('success', 'Customer berhasil dipulihkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $customer = Customer::withTrashed()->findOrFail($id);
            $customer->forceDelete();

            return redirect()->back()->with('success', 'Customer berhasil dihapus permanen');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:edit,delete',
            'selected' => 'required|array',
            'selected.*' => 'uuid', // Validasi semua id harus UUID
        ]);

        $action = $request->input('action');
        $selectedIds = $request->input('selected');

        // Cek dulu kalau gak ada data yang dipilih
        if (!$selectedIds || count($selectedIds) == 0) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        }

        try {
            // Aksi massal: Ubah status jadi "Aktif"
            if ($action === 'edit') {
                Customer::whereIn('id', $selectedIds)->update(['status' => 'aktif']);
                return redirect()->back()->with('success', 'Status berhasil diubah!');
            }

            // Aksi massal: Hapus data
            if ($action === 'delete') {
                Customer::whereIn('id', $selectedIds)->delete();
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            }

            // Kalau action gak dikenali
            return redirect()->back()->with('error', 'Aksi tidak valid!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
