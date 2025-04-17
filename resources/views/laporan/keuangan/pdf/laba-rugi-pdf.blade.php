<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Laba Rugi</title>
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

        .positive {
            color: green;
        }

        .negative {
            color: red;
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
        <div class="report-title">LAPORAN LABA RUGI</div>
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
                <td>: {{ $startDate }} sampai {{ $endDate }}</td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Laba Rugi -->
    <div class="summary-box">
        <div class="summary-title">RINGKASAN LABA RUGI</div>
        <div class="summary-grid">
            <div>
                <div class="summary-item">
                    <span>Total Pendapatan:</span>
                    <span>Rp {{ $totalPendapatan }}</span>
                </div>
                <div class="summary-item">
                    <span>Harga Pokok Penjualan:</span>
                    <span>Rp {{ $totalHPP }}</span>
                </div>
                <div class="summary-item" style="border-top: 1px dashed #ddd; padding-top: 3px; font-weight: bold;">
                    <span>Laba Kotor:</span>
                    <span class="positive">Rp {{ $labaKotor }}</span>
                </div>
            </div>
            <div>
                <div class="summary-item">
                    <span>Biaya Operasional:</span>
                    <span>Rp {{ $biayaOperasional }}</span>
                </div>
                <div class="summary-item">
                    <span>Pajak:</span>
                    <span>Rp {{ $totalPajak }}</span>
                </div>
                <div class="summary-item" style="border-top: 1px dashed #ddd; padding-top: 3px; font-weight: bold;">
                    <span>Laba Bersih:</span>
                    <span class="{{ $labaBersih > 0 ? 'positive' : 'negative' }}">Rp {{ $labaBersih }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Laba Rugi Harian -->
    <h3>Rincian Laba Rugi Harian</h3>
    <table border="1">
        <thead>
            <tr>
                <th width="10%">Tanggal</th>
                <th width="15%">Pendapatan</th>
                <th width="15%">HPP</th>
                <th width="15%">Laba Kotor</th>
                <th width="15%">Biaya Ops.</th>
                <th width="15%">Pajak</th>
                <th width="15%">Laba Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rincianLabaRugi as $item)
                <tr>
                    <td>{{ $item['tanggal'] }}</td>
                    <td class="text-right">Rp {{ $item['pendapatan'] }}</td>
                    <td class="text-right">Rp {{ $item['hpp'] }}</td>
                    <td class="text-right">Rp {{ $item['laba_kotor'] }}</td>
                    <td class="text-right">Rp {{ $item['biaya_operasional'] }}</td>
                    <td class="text-right">Rp {{ $item['pajak'] }}</td>
                    <td class="text-right">Rp {{ $item['laba_bersih'] }}</td>
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
                <td class="text-right">Rp {{ $totalPendapatan }}</td>
                <td class="text-right">Rp {{ $totalHPP }}</td>
                <td class="text-right">Rp {{ $labaKotor }}</td>
                <td class="text-right">Rp {{ $biayaOperasional }}</td>
                <td class="text-right">Rp {{ $totalPajak }}</td>
                <td class="text-right">Rp {{ $labaBersih }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tabel Produk Terlaris -->
    <h3>Produk dengan Keuntungan Tertinggi</h3>
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Nama Produk</th>
                <th width="10%">Total Terjual</th>
                <th width="15%">Pendapatan</th>
                <th width="15%">HPP</th>
                <th width="15%">Profit</th>
                <th width="10%">Margin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produkTerlaris as $index => $produk)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $produk->nama_barang }}</td>
                    <td class="text-right">{{ $produk->total_terjual }}</td>
                    <td class="text-right">Rp {{ $produk->total_pendapatan }}</td>
                    <td class="text-right">Rp {{ $produk->total_hpp }}</td>
                    <td class="text-right">Rp {{ $produk->profit }}</td>
                    <td class="text-right">{{ $produk->margin }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data produk terjual pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
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
                    Manager Keuangan
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan Laba Rugi - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
