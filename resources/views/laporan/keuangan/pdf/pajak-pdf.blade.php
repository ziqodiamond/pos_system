<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pajak {{ $periode }}</title>
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

        .currency {
            text-align: right;
        }

        .tax-table {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="company-name">{{ $toko['nama'] }}</div>
        <div class="company-address">{{ $toko['alamat'] }}, {{ $toko['kota'] }}, {{ $toko['provinsi'] }}</div>
        <div class="company-address">Telp: {{ $toko['telepon'] }} | Email: {{ $toko['email'] }}</div>
        <div class="report-title">LAPORAN PAJAK</div>
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
                <td>: {{ $periode }}</td>
            </tr>
        </table>
    </div>

    <!-- Ringkasan Pajak -->
    <div class="summary-box">
        <div class="summary-title">RINGKASAN PAJAK</div>
        <div class="summary-grid">
            <div>
                <div class="summary-item">
                    <span>Total Pajak Masukan:</span>
                    <span>Rp {{ number_format($totalPajakMasukan, 0, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Total Pajak Keluaran:</span>
                    <span>Rp {{ number_format($totalPajakKeluaran, 0, ',', '.') }}</span>
                </div>
            </div>
            <div>
                <div class="summary-item" style="border-top: 1px dashed #ddd; padding-top: 3px; font-weight: bold;">
                    <span>Pajak yang Dibayarkan:</span>
                    <span class="{{ $pajakDibayarkan >= 0 ? 'positive' : 'negative' }}">
                        Rp {{ number_format($pajakDibayarkan, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pajak Masukan -->
    <h3>Rincian Pajak Masukan</h3>
    <table border="1" class="tax-table">
        <thead>
            <tr>
                <th width="15%">No. Referensi</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Nama Barang</th>
                <th width="10%">% Pajak</th>
                <th width="10%">Jumlah</th>
                <th width="20%">Nilai Pajak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pajakMasukan as $item)
                <tr>
                    <td>{{ $item->no_ref }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-right">{{ $item->persentase_pajak }}%</td>
                    <td class="text-right">{{ $item->jumlah_barang }}</td>
                    <td class="currency">Rp {{ number_format($item->nilai_pajak, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data pajak masukan pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5">Total Pajak Masukan</td>
                <td class="currency">Rp {{ number_format($totalPajakMasukan, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tabel Pajak Keluaran -->
    <h3>Rincian Pajak Keluaran</h3>
    <table border="1" class="tax-table">
        <thead>
            <tr>
                <th width="15%">No. Referensi</th>
                <th width="15%">Tanggal</th>
                <th width="30%">Nama Barang</th>
                <th width="10%">% Pajak</th>
                <th width="10%">Jumlah</th>
                <th width="20%">Nilai Pajak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pajakKeluaran as $item)
                <tr>
                    <td>{{ $item->no_ref }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-right">{{ $item->persentase_pajak }}%</td>
                    <td class="text-right">{{ $item->jumlah_barang }}</td>
                    <td class="currency">Rp {{ number_format($item->nilai_pajak, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data pajak keluaran pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5">Total Pajak Keluaran</td>
                <td class="currency">Rp {{ number_format($totalPajakKeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Tabel Ringkasan Pajak per Kategori -->
    <h3>Ringkasan Pajak per Kategori</h3>
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Kategori Barang</th>
                <th width="20%">Total Kuantitas</th>
                <th width="20%">Total Penjualan</th>
                <th width="20%">Total Pajak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ringkasanPajakPerKategori as $index => $kategori)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kategori->kategori }}</td>
                    <td class="text-right">{{ $kategori->total_kuantitas }}</td>
                    <td class="currency">Rp {{ number_format($kategori->total_penjualan, 0, ',', '.') }}</td>
                    <td class="currency">Rp {{ number_format($kategori->total_pajak, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data pajak per kategori pada periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Total Pajak Keluaran per Kategori</td>
                <td class="currency">Rp {{ number_format($totalPajakKeluaran, 0, ',', '.') }}</td>
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
                    Manager Keuangan
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan Pajak Periode {{ $periode }} - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
