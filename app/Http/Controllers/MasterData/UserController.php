<?php

namespace App\Http\Controllers\MasterData;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BreadcrumbController;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Query dasar user
        $query = User::query();

        // Search with unique results
        if ($request->has('search') && $request->search != '') {
            $searchTerm = strtolower($request->search);
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(kode) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $searchTerm . '%']);
            })->distinct();
        }

        // Filter by role
        if ($request->has('role') && in_array($request->role, ['kasir', 'gudang', 'admin', 'super_admin'])) {
            $query->where('role', $request->role);
        }

        // Sortir dengan default 'terbaru'
        $sortOrder = $request->get('Urutkan', 'terbaru');
        if (in_array($sortOrder, ['terbaru', 'terlama'])) {
            $order = $sortOrder === 'terbaru' ? 'desc' : 'asc';
            $query->orderBy('created_at', $order);
        }

        $users = $query->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('master_data.user.partials.table', compact('users'))->render(),
                'pagination' => $users->links()->toHtml(),
            ]);
        }

        return view('master_data.user.index', compact('users', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,kasir,super_admin,gudang',
        ]);

        try {
            // Generate kode with format: TTBBYY00000001
            $date = now()->format('d');  // Get current date (01-31)
            $month = now()->format('m'); // Get current month (01-12)
            $year = now()->format('y');  // Get current year (23)

            $prefix = $date . $month . $year;

            $latestUser = User::orderBy('kode', 'desc')
                ->where('kode', 'like', $prefix . '%')
                ->first();

            $lastNumber = $latestUser ? intval(substr($latestUser->kode, -8)) : 0;
            $newNumber = str_pad($lastNumber + 1, 8, '0', STR_PAD_LEFT);
            $kode = $prefix . $newNumber;

            $user = new User();
            $user->kode = $kode;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->email_verified_at = \Carbon\Carbon::now();
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->save();

            return redirect()->back()->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:6',
        ]);

        try {
            $user = User::findOrFail($id);

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($user->isDirty()) {
                $user->update($userData);
                return redirect()->back()->with('success', 'User berhasil diperbarui');
            }

            return redirect()->back()->with('info', 'Tidak ada perubahan data');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->back()->with('success', 'User berhasil dipulihkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->forceDelete();

            return redirect()->back()->with('success', 'User berhasil dihapus permanen');
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
        ]);

        $action = $request->input('action');
        $selectedString = $request->input('selected');
        $newStatus = $request->input('status');

        if (empty($selectedString)) {
            return redirect()->back()->with('error', 'Tidak ada data yang dipilih!');
        }

        $selectedIds = array_filter(array_map('trim', explode(',', $selectedString)));

        try {
            switch ($action) {
                case 'edit':
                    if ($newStatus) {
                        User::whereIn('id', $selectedIds)->update(['status' => $newStatus]);
                        return redirect()->back()->with('success', 'Status user berhasil diubah!');
                    }
                    return redirect()->back()->with('error', 'Status baru harus dipilih!');

                case 'delete':
                    User::whereIn('id', $selectedIds)->delete();
                    return redirect()->back()->with('success', 'User berhasil dihapus!');


                default:
                    return redirect()->back()->with('error', 'Aksi tidak valid!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menghasilkan laporan PDF data user
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(Request $request)
    {
        // Mempersiapkan query untuk data user
        $query = User::query();

        // Cek apakah menampilkan data yang sudah dihapus
        if ($request->has('status') && $request->status === 'deleted') {
            $query->onlyTrashed(); // Menampilkan data yang sudah di-soft delete
        }

        // Filter berdasarkan role jika ada
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan nama jika ada
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Urutkan data
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Ambil semua data user yang sesuai filter
        $users = $query->with(['penjualan', 'pembelian'])->get();

        // Hitung total transaksi (penjualan dan pembelian)
        $totalPenjualan = $users->sum(function ($user) {
            return $user->penjualan->count();
        });

        $totalPembelian = $users->sum(function ($user) {
            return $user->pembelian->count();
        });

        $totalTransaksi = $totalPenjualan + $totalPembelian;

        // Ambil data pengaturan toko dari database
        $settingQuery = Setting::whereIn('key', ['toko_nama', 'toko_alamat', 'toko_kota', 'toko_provinsi', 'toko_telepon', 'toko_email', 'toko_npwp'])
            ->pluck('value', 'key')
            ->toArray();

        // Siapkan data toko untuk laporan
        $toko = [
            'nama' => $settingQuery['toko_nama'] ?? env('TOKO_NAMA', 'Nama Toko'),
            'alamat' => $settingQuery['toko_alamat'] ?? env('TOKO_ALAMAT', 'Alamat Toko'),
            'kota' => $settingQuery['toko_kota'] ?? env('TOKO_KOTA', 'Jakarta'),
            'provinsi' => $settingQuery['toko_provinsi'] ?? env('TOKO_PROVINSI', 'DKI Jakarta'),
            'telepon' => $settingQuery['toko_telepon'] ?? env('TOKO_TELEPON', 'Telepon Toko'),
            'email' => $settingQuery['toko_email'] ?? env('TOKO_EMAIL', 'Email Toko'),
            'npwp' => $settingQuery['toko_npwp'] ?? env('TOKO_NPWP', 'NPWP Toko'),
        ];

        // Siapkan data untuk view PDF
        $data = [
            'users' => $users,
            'tanggal_cetak' => now()->format('d-m-Y H:i:s'),
            'filter' => $request->all(),
            'total_penjualan' => $totalPenjualan,
            'total_pembelian' => $totalPembelian,
            'total_transaksi' => $totalTransaksi,
            'total_items' => $users->count(),
            'toko' => $toko,
        ];

        // Load view PDF dan convert ke PDF
        $pdf = PDF::loadView('master_data.user.pdf.user-pdf', $data);

        // Atur paper size ke A4
        $pdf->setPaper('a4', 'portrait');

        // Download PDF dengan nama dinamis
        return $pdf->stream('laporan-user-' . now()->format('dmY-His') . '.pdf');
    }
}
