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

        if ($request->has('Status') && $request->Status === 'deleted') {
            $query->onlyTrashed(); // Menampilkan data yang sudah di-soft delete
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(kode) LIKE ?', ['%' . strtolower($request->search) . '%']);
            });
        }

        // Filter status
        if ($request->has('Status') && in_array($request->Status, ['active', 'inactive'])) {
            $query->where('status', $request->Status);
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

        try {
            // Generate customer code with format CST-YYYYMM-XXXX
            $latestCustomer = Customer::orderBy('created_at', 'desc')->first();
            $currentDate = now()->format('Ym');

            if ($latestCustomer && str_contains($latestCustomer->kode, $currentDate)) {
                $lastNumber = (int) substr($latestCustomer->kode, -4);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            // Simpan customer baru
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'email' => 'required|email|unique:customers,email,' . $id,
            'tanggal_lahir' => 'required|date',
        ]);

        try {
            $customer = Customer::findOrFail($id);

            // Perbarui data pada model
            $customer->fill([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'tanggal_lahir' => $request->tanggal_lahir,
                'status' => $request->filled('status') ? 'active' : 'inactive',
            ]);

            // Cek apakah ada perubahan data
            if ($customer->isDirty()) {
                $customer->save();
                return redirect()->back()->with('success', 'Customer berhasil diperbarui');
            }

            return redirect()->back()->with('info', 'Tidak ada perubahan data');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
            'action' => 'required|in:edit,delete,restore,forceDelete',
            'selected' => 'required|string',
            'status' => 'nullable|in:active,inactive'
        ], [
            'action.required' => 'Aksi wajib dipilih!',
            'action.in' => 'Aksi yang dipilih tidak valid!',
            'selected.required' => 'Tidak ada data yang dipilih!',
            'selected.string' => 'Format data tidak valid!',
            'status.in' => 'Status harus berupa "active" atau "inactive"!'
        ]);

        $action = $request->input('action');
        $selectedString = $request->input('selected');
        $newStatus = $request->input('status');

        if (empty($selectedString)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        }

        $selectedIds = array_filter(array_map('trim', explode(',', $selectedString)));

        foreach ($selectedIds as $id) {
            if (!preg_match('/^[a-f0-9\-]{36}$/', $id)) {
                return redirect()->back()->with('error', 'ID tidak valid!');
            }
        }

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        }

        try {
            switch ($action) {
                case 'edit':
                    if ($newStatus) {
                        Customer::whereIn('id', $selectedIds)->update(['status' => $newStatus]);
                        return redirect()->back()->with('success', 'Status customer berhasil diubah!');
                    }
                    return redirect()->back()->with('error', 'Status baru harus dipilih!');

                case 'delete':
                    Customer::whereIn('id', $selectedIds)->delete();
                    return redirect()->back()->with('success', 'Customer berhasil dihapus!');

                case 'restore':
                    Customer::withTrashed()->whereIn('id', $selectedIds)->restore();
                    return redirect()->back()->with('success', 'Customer berhasil direstore!');

                case 'forceDelete':
                    Customer::withTrashed()->whereIn('id', $selectedIds)->forceDelete();
                    return redirect()->back()->with('success', 'Customer berhasil dihapus permanen!');

                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
