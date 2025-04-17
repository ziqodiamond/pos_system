<?php

namespace App\Http\Controllers\Laporan;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\FakturPembelian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BreadcrumbController;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = BreadcrumbController::generateBreadcrumbs();

        // Mendapatkan periode dari request, default: hari ini
        $periode = $request->periode ?? 'hari_ini';

        // Menentukan rentang tanggal berdasarkan periode
        $range = $this->getPeriodeRange($periode);
        $start_date = $range['start'];
        $end_date = $range['end'];

        // Mengambil statistik penjualan
        $total_penjualan = Penjualan::whereBetween('created_at', [$start_date, $end_date])->count();
        $omset = Penjualan::whereBetween('created_at', [$start_date, $end_date])->sum('grand_total');

        // Mengambil statistik pembelian
        $total_pembelian = Pembelian::whereBetween('tanggal_pembelian', [$start_date, $end_date])->count();
        $total_pengeluaran = Pembelian::whereBetween('tanggal_pembelian', [$start_date, $end_date])->sum('total');

        // Mengambil jumlah member/customer
        $total_member = Customer::count();

        // Mengambil statistik master data
        $total_barang = Barang::count();
        $total_supplier = Supplier::count();
        $total_customer = Customer::count();
        $total_kategori = Kategori::count();

        // Mengambil data untuk grafik penjualan per bulan (12 bulan terakhir)
        $penjualan_bulanan = $this->getPenjualanBulanan();

        // Mengambil data untuk grafik top 5 produk terlaris
        $produk_terlaris = $this->getProdukTerlaris($start_date, $end_date);

        // Menghitung laba kotor (omset - pengeluaran)
        $laba_kotor = $omset - $total_pengeluaran;

        // Jika request AJAX, kembalikan data JSON
        if ($request->ajax()) {
            return response()->json([
                'total_penjualan' => $total_penjualan,
                'omset' => $omset,
                'total_pembelian' => $total_pembelian,
                'total_pengeluaran' => $total_pengeluaran,
                'laba_kotor' => $laba_kotor,
                'produk_terlaris' => $produk_terlaris,
                'penjualan_bulanan' => $penjualan_bulanan
            ]);
        }

        return view('laporan.index', compact(
            'breadcrumbs',
            'periode',
            'total_penjualan',
            'omset',
            'total_pembelian',
            'total_pengeluaran',
            'total_member',
            'total_barang',
            'total_supplier',
            'total_customer',
            'total_kategori',
            'penjualan_bulanan',
            'produk_terlaris',
            'laba_kotor'
        ));
    }

    /**
     * Mendapatkan range tanggal berdasarkan periode yang dipilih
     * @param string $periode
     * @return array
     */
    private function getPeriodeRange($periode)
    {
        switch ($periode) {
            case 'hari_ini':
                $start = Carbon::today();
                $end = Carbon::today()->endOfDay();
                break;
            case 'kemarin':
                $start = Carbon::yesterday();
                $end = Carbon::yesterday()->endOfDay();
                break;
            case 'minggu_ini':
                $start = Carbon::now()->startOfWeek();
                $end = Carbon::now()->endOfWeek();
                break;
            case 'minggu_kemarin':
                $start = Carbon::now()->subWeek()->startOfWeek();
                $end = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'bulan_ini':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                break;
            case 'bulan_kemarin':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'tahun_ini':
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                break;
            case 'tahun_kemarin':
                $start = Carbon::now()->subYear()->startOfYear();
                $end = Carbon::now()->subYear()->endOfYear();
                break;
            default:
                $start = Carbon::today();
                $end = Carbon::today()->endOfDay();
        }

        return [
            'start' => $start,
            'end' => $end
        ];
    }

    /**
     * Mendapatkan data penjualan bulanan untuk 12 bulan terakhir
     * @return array
     */
    private function getPenjualanBulanan()
    {
        $result = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            $year = $month->format('Y');

            $total = Penjualan::whereYear('created_at', $year)
                ->whereMonth('created_at', $month->month)
                ->sum('grand_total');

            $result[] = [
                'bulan' => $monthName,
                'total' => $total
            ];
        }

        return $result;
    }

    /**
     * Mendapatkan 5 produk terlaris dalam periode tertentu
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return \Illuminate\Support\Collection
     */
    private function getProdukTerlaris($start_date, $end_date)
    {
        return DB::table('detail_penjualans')
            ->join('penjualans', 'detail_penjualans.penjualan_id', '=', 'penjualans.id')
            ->join('barangs', 'detail_penjualans.barang_id', '=', 'barangs.id')
            ->select('barangs.nama', DB::raw('SUM(detail_penjualans.kuantitas) as total_terjual'))
            ->whereBetween('penjualans.created_at', [$start_date, $end_date])
            ->groupBy('barangs.id', 'barangs.nama')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * API untuk mendapatkan data statistik berdasarkan periode
     */
    public function getStatistikByPeriode(Request $request)
    {
        $periode = $request->periode ?? 'hari_ini';

        // Menentukan rentang tanggal berdasarkan periode
        $range = $this->getPeriodeRange($periode);
        $start_date = $range['start'];
        $end_date = $range['end'];

        // Mengambil statistik penjualan
        $total_penjualan = Penjualan::whereBetween('created_at', [$start_date, $end_date])->count();
        $omset = Penjualan::whereBetween('created_at', [$start_date, $end_date])->sum('grand_total');

        // Mengambil statistik pembelian
        $total_pembelian = Pembelian::whereBetween('tanggal_pembelian', [$start_date, $end_date])->count();
        $total_pengeluaran = Pembelian::whereBetween('tanggal_pembelian', [$start_date, $end_date])->sum('total');

        // Menghitung laba kotor (omset - pengeluaran)
        $laba_kotor = $omset - $total_pengeluaran;

        // Mengambil data untuk grafik penjualan bulanan
        $penjualan_bulanan = $this->getPenjualanBulanan();

        // Mengambil data untuk grafik top 5 produk terlaris
        $produk_terlaris = $this->getProdukTerlaris($start_date, $end_date);

        return response()->json([
            'total_penjualan' => $total_penjualan,
            'omset' => $omset,
            'total_pembelian' => $total_pembelian,
            'total_pengeluaran' => $total_pengeluaran,
            'laba_kotor' => $laba_kotor,
            'produk_terlaris' => $produk_terlaris,
            'penjualan_bulanan' => $penjualan_bulanan
        ]);
    }
}
