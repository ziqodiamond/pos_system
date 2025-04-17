<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Barang</title>
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

        /* Pewarnaan stok */
        .stok-warning {
            background-color: #fff3cd;
        }
    </style>
</head>

<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="company-name">PT. SISTEM POS INDONESIA</div>
        <div class="company-address">Jl. Contoh No. 123, Jakarta Selatan</div>
        <div class="company-address">Telp: (021) 1234567 | Email: info@posapp.com</div>
        <div class="report-title">LAPORAN DATA BARANG</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td width="120">Total Item</td>
                <td>: {{ $total_items }} barang</td>
            </tr>
            <tr>
                <td>Filter</td>
                <td>:
                    @if (!empty($filter['kategori_id']))
                        Kategori: {{ \App\Models\Kategori::find($filter['kategori_id'])->nama ?? 'Semua' }} |
                    @endif

                    @if (isset($filter['status']))
                        Status: {{ $filter['status'] == '1' ? 'Aktif' : 'Tidak Aktif' }} |
                    @endif

                    @if (isset($filter['filter_stok_minimum']))
                        Hanya Stok Di Bawah Minimum |
                    @endif

                    Urut: {{ ucfirst($filter['sort_by'] ?? 'nama') }}
                    ({{ ($filter['sort_order'] ?? 'asc') == 'asc' ? 'A-Z' : 'Z-A' }})
                </td>
                <td>Total Nilai Inventory</td>
                <td>: Rp {{ number_format($total_nilai_inventory, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data Barang -->
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="20%">Nama Barang</th>
                <th width="15%">Kategori</th>
                <th width="5%">Satuan</th>
                <th width="5%">Stok</th>
                <th width="5%">Min</th>
                <th width="10%">Harga Beli</th>
                <th width="10%">Harga Pokok</th>
                <th width="10%">Harga Jual</th>
                <th width="5%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangs as $index => $barang)
                <tr @if ($barang->stok <= $barang->stok_minimum) class="stok-warning" @endif>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $barang->kode }}</td>
                    <td>{{ $barang->nama }}</td>
                    <td>{{ $barang->kategori->nama ?? '-' }}</td>
                    <td>{{ $barang->satuan->nama ?? '-' }}</td>
                    <td class="text-right">{{ $barang->stok }}</td>
                    <td class="text-right">{{ $barang->stok_minimum }}</td>
                    <td class="text-right">{{ number_format($barang->harga_beli, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($barang->harga_pokok, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($barang->harga_jual, 0, ',', '.') }}</td>
                    <td class="{{ $barang->status ? 'status-active' : 'status-inactive' }}">
                        {{ $barang->status ? 'Aktif' : 'Non-Aktif' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align: center;">Tidak ada data barang yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5">Total</td>
                <td class="text-right">{{ $barangs->sum('stok') }}</td>
                <td></td>
                <td class="text-right">{{ number_format($barangs->sum('harga_beli'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($barangs->sum('harga_pokok'), 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($barangs->sum('harga_jual'), 0, ',', '.') }}</td>
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
                    Jakarta, {{ date('d F Y') }}
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
        Laporan Data Barang - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
