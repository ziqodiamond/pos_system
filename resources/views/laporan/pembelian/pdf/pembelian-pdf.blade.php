<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
    <style>
        /* Styling untuk dokumen PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-address {
            font-size: 11px;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0 5px;
        }

        .meta-info {
            margin: 10px 0;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 5px;
            font-size: 10px;
        }

        table th {
            background-color: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }

        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-number {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 9px;
        }

        .text-right {
            text-align: right;
        }

        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }

        .summary-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        h3 {
            font-size: 13px;
            margin: 15px 0 5px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
        }
    </style>
</head>

<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="company-name">{{ $toko['nama'] }}</div>
        <div class="company-address">{{ $toko['alamat'] }}, {{ $toko['kota'] }}, {{ $toko['provinsi'] }}</div>
        <div class="company-address">Telp: {{ $toko['telepon'] }} | Email: {{ $toko['email'] }}</div>
        <div class="report-title">LAPORAN PEMBELIAN</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: {{ $startDateFormatted }} sampai {{ $endDateFormatted }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>: {{ empty($status) ? 'Semua' : $status }}</td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Pembelian -->
    <div class="summary-box">
        <div class="summary-title">RINGKASAN PEMBELIAN</div>
        <div class="summary-grid">
            <div>
                <div class="summary-item">
                    <span>Total Nilai Pembelian:</span>
                    <span>Rp {{ number_format($totalPembelian / 100, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Barang Dibeli:</span>
                    <span>{{ number_format($totalBarangDibeli, 0, ',', '.') }} unit</span>
                </div>
                <div class="summary-item">
                    <span>Jumlah Transaksi:</span>
                    <span>{{ count($pembelian) }} transaksi</span>
                </div>
            </div>
            <div>
                <div class="summary-item">
                    <span>Total Pajak:</span>
                    <span>Rp {{ number_format($totalPajak / 100, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Diskon:</span>
                    <span>Rp {{ number_format($totalDiskon / 100, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Biaya Lainnya:</span>
                    <span>Rp {{ number_format($totalBiayaLainnya / 100, 2, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Pembelian Harian -->
    <h3>Rincian Pembelian Harian</h3>
    <table border="1">
        <thead>
            <tr>
                <th width="15%">Tanggal</th>
                <th width="15%">Total Pembelian</th>
                <th width="12%">Total Barang</th>
                <th width="12%">Pajak</th>
                <th width="12%">Diskon</th>
                <th width="12%">Biaya Lainnya</th>
                <th width="12%">Jumlah Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rincianPembelian as $item)
                <tr>
                    <td>{{ $item['tanggal'] }}</td>
                    <td class="text-right">Rp {{ number_format($item['total_pembelian'] / 100, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item['total_barang'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['pajak'] / 100, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['diskon'] / 100, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item['biaya_lainnya'] / 100, 2, ',', '.') }}</td>
                    <td class="text-right">{{ $item['jumlah_transaksi'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data transaksi pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>Total</td>
                <td class="text-right">Rp {{ number_format($totalPembelian / 100, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalBarangDibeli, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalPajak / 100, 2, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalDiskon / 100, 2, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($totalBiayaLainnya / 100, 2, ',', '.') }}</td>
                <td class="text-right">{{ count($pembelian) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tabel Barang Terbanyak Dibeli -->
    <h3>Barang Terbanyak Dibeli</h3>
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Barang</th>
                <th width="20%">Jumlah</th>
                <th width="20%">Total Nilai</th>
                <th width="20%">Rata-rata Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangTerbanyak as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->barang->nama ?? 'Barang tidak ditemukan' }}</td>
                    <td class="text-right">{{ number_format($item->total_qty, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_value / 100, 2, ',', '.') }}</td>
                    <td class="text-right">Rp
                        {{ $item->total_qty > 0 ? number_format($item->total_value / $item->total_qty / 100, 2, ',', '.') : 0 }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data barang dibeli pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tabel Daftar Transaksi Pembelian -->
    <h3>Daftar Transaksi Pembelian</h3>
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">No. Referensi</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Supplier</th>
                <th width="10%">Status</th>
                <th width="12%">Subtotal</th>
                <th width="10%">Diskon</th>
                <th width="10%">Pajak</th>
                <th width="14%">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembelian as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->no_ref }}</td>
                    <td>{{ Carbon\Carbon::parse($item->tanggal_pembelian)->format('d-m-Y') }}</td>
                    <td>{{ $item->supplier->nama ?? 'Supplier tidak ditemukan' }}</td>
                    <td>{{ $item->status }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal / 100, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->diskon_value / 100, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->pajak_value / 100, 2, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->total / 100, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data transaksi pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5">Total</td>
                <td class="text-right">Rp {{ number_format($pembelian->sum('subtotal') / 100, 2, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($pembelian->sum('diskon_value') / 100, 2, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($pembelian->sum('pajak_value') / 100, 2, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($pembelian->sum('total') / 100, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <div style="margin-top: 30px;">
        <table border="0" width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%" style="text-align: center;">
                    {{ $toko['kota'] }}, {{ date('d F Y') }}
                    <br><br><br><br>
                    (___________________)
                    <br>
                    Manager Pembelian
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan Pembelian - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
