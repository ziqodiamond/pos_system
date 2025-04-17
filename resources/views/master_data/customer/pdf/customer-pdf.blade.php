<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Customer</title>
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
        <div class="report-title">LAPORAN DATA CUSTOMER</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td width="120">Total Customer</td>
                <td>: {{ $total_items }} orang</td>
            </tr>
            <tr>
                <td>Filter</td>
                <td>:
                    @if (isset($filter['status']) && $filter['status'] === 'deleted')
                        Data Terhapus |
                    @elseif (isset($filter['status']))
                        Status: {{ $filter['status'] == '1' ? 'Aktif' : 'Tidak Aktif' }} |
                    @endif

                    @if (isset($filter['tanggal_lahir_start']) && isset($filter['tanggal_lahir_end']))
                        Tanggal Lahir: {{ $filter['tanggal_lahir_start'] }} s/d {{ $filter['tanggal_lahir_end'] }} |
                    @endif

                    Urut: {{ ucfirst($filter['sort_by'] ?? 'nama') }}
                    ({{ ($filter['sort_order'] ?? 'asc') == 'asc' ? 'A-Z' : 'Z-A' }})
                </td>
                <td>Total Transaksi</td>
                <td>: {{ number_format($total_transaksi, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data Customer -->
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="20%">Nama Customer</th>
                <th width="20%">Alamat</th>
                <th width="12%">Telepon</th>
                <th width="15%">Email</th>
                <th width="10%">Tanggal Lahir</th>
                <th width="8%">Transaksi</th>
                <th width="5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->kode }}</td>
                    <td>{{ $customer->nama }}</td>
                    <td>{{ $customer->alamat }}</td>
                    <td>{{ $customer->telepon }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->tanggal_lahir ? date('d-m-Y', strtotime($customer->tanggal_lahir)) : '-' }}</td>
                    <td class="text-right">{{ $customer->penjualan->count() }}</td>
                    <td class="{{ $customer->status ? 'status-active' : 'status-inactive' }}">
                        {{ $customer->status ? 'Aktif' : 'Non-Aktif' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data customer yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="7">Total</td>
                <td class="text-right">{{ $total_transaksi }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- Ringkasan -->
    <div style="margin-top: 20px;">
        <table border="0" width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%" style="text-align: center;">
                    {{ $toko['kota'] }}, {{ date('d F Y') }}
                    <br><br><br><br>
                    (___________________)
                    <br>
                    Manager Customer Relations
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan Data Customer - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
