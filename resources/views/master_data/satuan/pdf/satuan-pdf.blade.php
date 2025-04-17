<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Satuan</title>
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

        /* Pewarnaan status */
        .status-active {
            color: green;
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
        <div class="report-title">LAPORAN DATA SATUAN</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td width="120">Total Satuan</td>
                <td>: {{ $total_items }} satuan</td>
            </tr>
            <tr>
                <td>Filter</td>
                <td>:
                    @if (isset($filter['status']) && $filter['status'] === 'deleted')
                        Data Terhapus |
                    @elseif (isset($filter['status']))
                        Status: {{ $filter['status'] == '1' ? 'Aktif' : 'Tidak Aktif' }} |
                    @endif

                    @if (isset($filter['status_satuan']) && !empty($filter['status_satuan']))
                        Status Satuan: {{ $filter['status_satuan'] }} |
                    @endif

                    Urut: {{ ucfirst($filter['sort_by'] ?? 'nama') }}
                    ({{ ($filter['sort_order'] ?? 'asc') == 'asc' ? 'A-Z' : 'Z-A' }})
                </td>
                <td>Total Data</td>
                <td>: {{ number_format($total_items, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data Satuan -->
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="20%">Nama Satuan</th>
                <th width="20%">Status Satuan</th>
                <th width="30%">Keterangan</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($satuans as $index => $satuan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $satuan->kode }}</td>
                    <td>{{ $satuan->nama }}</td>
                    <td>{{ $satuan->status_satuan }}</td>
                    <td>{{ $satuan->keterangan ?: '-' }}</td>
                    <td class="{{ $satuan->status ? 'status-active' : 'status-inactive' }}">
                        {{ $satuan->status ? 'Aktif' : 'Non-Aktif' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data satuan yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5">Total Data Satuan</td>
                <td class="text-right">{{ $total_items }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Catatan Satuan -->
    @if (count($satuans) > 0)
        <div style="margin-top: 15px; font-size: 11px;">
            <strong>Catatan Satuan:</strong>
            <table border="1" style="margin-top: 5px;">
                <thead>
                    <tr>
                        <th width="15%">Kode</th>
                        <th width="20%">Nama Satuan</th>
                        <th width="65%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($satuans as $satuan)
                        @if (!empty($satuan->keterangan))
                            <tr>
                                <td>{{ $satuan->kode }}</td>
                                <td>{{ $satuan->nama }}</td>
                                <td>{{ $satuan->keterangan }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

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
        Laporan Data Satuan - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
