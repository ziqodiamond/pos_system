<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kategori</title>
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

        .filter-info {
            margin: 10px 0;
            font-size: 10px;
            font-style: italic;
        }

        /* Status kategori */
        .status-active {
            color: green;
            font-weight: bold;
        }

        .status-inactive {
            color: red;
        }
    </style>
</head>

<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="company-name">{{ $toko['nama'] }}</div>
        <div class="company-address">{{ $toko['alamat'] }}, {{ $toko['kota'] }}, {{ $toko['provinsi'] }}</div>
        <div class="company-address">Telp: {{ $toko['telepon'] }} | Email: {{ $toko['email'] }}</div>
        <div class="report-title">LAPORAN DATA KATEGORI</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td width="120">Total Kategori</td>
                <td>: {{ $total_items }} data</td>
            </tr>
            <tr>
                <td>Filter</td>
                <td>:
                    @if (isset($filter['status']) && !empty($filter['status']))
                        Status: {{ ucfirst($filter['status']) }} |
                    @endif

                    @if (isset($filter['kode']) && !empty($filter['kode']))
                        Kode: {{ $filter['kode'] }} |
                    @endif

                    @if (isset($filter['nama']) && !empty($filter['nama']))
                        Nama: {{ $filter['nama'] }} |
                    @endif

                    Urut: {{ ucfirst($filter['sort_by'] ?? 'id') }}
                    ({{ ($filter['sort_order'] ?? 'asc') == 'asc' ? 'A-Z' : 'Z-A' }})
                </td>
                <td>Total Barang</td>
                <td>: {{ number_format($total_barang, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data Kategori -->
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="25%">Nama Kategori</th>
                <th width="10%">Status</th>
                <th width="15%">Jumlah Barang</th>
                <th width="35%">Daftar Barang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategoris as $index => $kategori)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $kategori->kode }}</td>
                    <td>{{ $kategori->nama }}</td>
                    <td>
                        @if ($kategori->status == 'active')
                            <span class="status-active">Aktif</span>
                        @else
                            <span class="status-inactive">Nonaktif</span>
                        @endif
                    </td>
                    <td class="text-right">{{ $kategori->barang_count }}</td>
                    <td>
                        @if ($kategori->barang_count > 0)
                            @php
                                $barangList = $kategori->barang->pluck('nama')->take(5)->toArray();
                                $totalBarang = $kategori->barang_count;
                            @endphp
                            {{ implode(', ', $barangList) }}
                            @if ($totalBarang > 5)
                                ... (dan {{ $totalBarang - 5 }} barang lainnya)
                            @endif
                        @else
                            <em>Tidak ada barang terkait</em>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data kategori yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Total Kategori</td>
                <td class="text-right">{{ $total_items }}</td>
                <td>Total Barang: {{ number_format($total_barang, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Informasi Tambahan -->
    <div style="margin-top: 15px; font-size: 11px;">
        <p><strong>Catatan:</strong></p>
        <ul style="margin-top: 5px; padding-left: 20px;">
            <li>Kategori dengan status <span class="status-active">Aktif</span> dapat digunakan dalam transaksi</li>
            <li>Kategori dengan status <span class="status-inactive">Nonaktif</span> tidak dapat digunakan dalam
                transaksi</li>
            <li>Jumlah barang menunjukkan banyaknya barang yang terdaftar pada kategori tersebut</li>
        </ul>
    </div>

    <!-- Tanda Tangan -->
    <div style="margin-top: 20px;">
        <table border="0" width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%" style="text-align: center;">
                    {{ $toko['kota'] }}, {{ date('d F Y') }}
                    <br><br><br><br>
                    (___________________)
                    <br>
                    Manager Inventory
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan Data Kategori - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
