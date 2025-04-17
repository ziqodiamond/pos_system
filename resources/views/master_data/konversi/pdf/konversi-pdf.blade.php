<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Konversi Satuan</title>
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

        /* Tipe konversi */
        .tipe-universal {
            color: blue;
            font-weight: bold;
        }

        .tipe-barang {
            color: green;
        }
    </style>
</head>

<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="company-name">{{ $toko['nama'] }}</div>
        <div class="company-address">{{ $toko['alamat'] }}, {{ $toko['kota'] }}, {{ $toko['provinsi'] }}</div>
        <div class="company-address">Telp: {{ $toko['telepon'] }} | Email: {{ $toko['email'] }}</div>
        <div class="report-title">LAPORAN DATA KONVERSI SATUAN</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td width="120">Total Konversi</td>
                <td>: {{ $total_items }} data</td>
            </tr>
            <tr>
                <td>Filter</td>
                <td>:
                    @if (isset($filter['tipe_konversi']) && $filter['tipe_konversi'] === 'universal')
                        Tipe: Universal |
                    @elseif (isset($filter['tipe_konversi']) && $filter['tipe_konversi'] === 'barang')
                        Tipe: Per Barang |
                    @endif

                    @if (isset($filter['barang_id']) && !empty($filter['barang_id']))
                        Barang: {{ $filter['barang_nama'] ?? $filter['barang_id'] }} |
                    @endif

                    @if (isset($filter['satuan_id']) && !empty($filter['satuan_id']))
                        Satuan Asal: {{ $filter['satuan_nama'] ?? $filter['satuan_id'] }} |
                    @endif

                    Urut: {{ ucfirst($filter['sort_by'] ?? 'id') }}
                    ({{ ($filter['sort_order'] ?? 'asc') == 'asc' ? 'A-Z' : 'Z-A' }})
                </td>
                <td>Total Data</td>
                <td>: {{ number_format($total_items, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data Konversi -->
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Barang</th>
                <th width="15%">Satuan Asal</th>
                <th width="15%">Nilai Konversi</th>
                <th width="15%">Satuan Tujuan</th>
                <th width="15%">Tipe Konversi</th>
                <th width="15%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($konversis as $index => $konversi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if ($konversi->barang_id)
                            {{ $konversi->barang->nama ?? $konversi->barang_id }}
                        @else
                            <span class="tipe-universal">Universal</span>
                        @endif
                    </td>
                    <td>{{ $konversi->satuan->nama ?? $konversi->satuan_id }}</td>
                    <td class="text-right">{{ number_format($konversi->nilai_konversi, 0, ',', '.') }}</td>
                    <td>{{ $konversi->satuanTujuan->nama ?? $konversi->satuan_tujuan_id }}</td>
                    <td>
                        @if ($konversi->barang_id)
                            <span class="tipe-barang">Per Barang</span>
                        @else
                            <span class="tipe-universal">Universal</span>
                        @endif
                    </td>
                    <td>
                        @if ($konversi->barang_id)
                            1 {{ $konversi->satuan->nama ?? '' }} =
                            {{ number_format($konversi->nilai_konversi, 0, ',', '.') }}
                            {{ $konversi->satuanTujuan->nama ?? '' }} ({{ $konversi->barang->nama ?? '' }})
                        @else
                            1 {{ $konversi->satuan->nama ?? '' }} =
                            {{ number_format($konversi->nilai_konversi, 0, ',', '.') }}
                            {{ $konversi->satuanTujuan->nama ?? '' }} (Universal)
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data konversi satuan yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6">Total Data Konversi</td>
                <td class="text-right">{{ $total_items }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Informasi Tambahan -->
    <div style="margin-top: 15px; font-size: 11px;">
        <p><strong>Catatan:</strong></p>
        <ul style="margin-top: 5px; padding-left: 20px;">
            <li>Konversi <span class="tipe-universal">Universal</span> berlaku untuk semua barang</li>
            <li>Konversi <span class="tipe-barang">Per Barang</span> hanya berlaku untuk barang tertentu</li>
            <li>Konversi per barang memiliki prioritas lebih tinggi daripada konversi universal</li>
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
        Laporan Data Konversi Satuan - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
