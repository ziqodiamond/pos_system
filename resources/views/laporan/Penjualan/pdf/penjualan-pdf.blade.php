<!-- resources/views/penjualan/pdf.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
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

        /* Pewarnaan metode pembayaran */
        .payment-cash {
            color: green;
        }

        .payment-transfer {
            color: blue;
        }

        .payment-card {
            color: purple;
        }
    </style>
</head>

<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="company-name">PT. SISTEM POS INDONESIA</div>
        <div class="company-address">Jl. Contoh No. 123, Jakarta Selatan</div>
        <div class="company-address">Telp: (021) 1234567 | Email: info@posapp.com</div>
        <div class="report-title">LAPORAN PENJUALAN</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td width="120">Total Transaksi</td>
                <td>: {{ $total_items }} transaksi</td>
            </tr>
            <tr>
                <td>Filter</td>
                <td>:
                    Periode: {{ \Carbon\Carbon::parse($filter['tanggal_mulai'])->format('d/m/Y') }} -
                    {{ \Carbon\Carbon::parse($filter['tanggal_akhir'])->format('d/m/Y') }} |

                    @if (!empty($filter['kasir_id']))
                        Kasir: {{ $nama_kasir ?? 'Semua' }} |
                    @endif

                    @if (!empty($filter['metode_pembayaran']))
                        Metode Pembayaran: {{ ucfirst($filter['metode_pembayaran']) }} |
                    @endif

                    Urut: {{ ucfirst($filter['sort_by']) }}
                    ({{ $filter['sort_order'] == 'asc' ? 'A-Z' : 'Z-A' }})
                </td>
                <td>Total Nilai Penjualan</td>
                <td>: Rp {{ number_format($total_penjualan / 100, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data Penjualan -->
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">No. Ref</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Kasir</th>
                <th width="15%">Customer</th>
                <th width="10%">Metode</th>
                <th width="10%">Subtotal</th>
                <th width="8%">Diskon</th>
                <th width="8%">Pajak</th>
                <th width="10%">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualans as $index => $penjualan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $penjualan->no_ref }}</td>
                    <td>{{ \Carbon\Carbon::parse($penjualan->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $penjualan->kasir->name ?? '-' }}</td>
                    <td>{{ $penjualan->customer->nama ?? 'Umum' }}</td>
                    <td class="{{ 'payment-' . strtolower($penjualan->metode_pembayaran) }}">
                        {{ ucfirst($penjualan->metode_pembayaran) }}
                    </td>
                    <td class="text-right">{{ number_format($penjualan->subtotal / 100, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($penjualan->total_diskon / 100, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($penjualan->total_pajak / 100, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($penjualan->grand_total / 100, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data penjualan yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6">Total</td>
                <td class="text-right">{{ number_format($penjualans->sum('subtotal') / 100, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($penjualans->sum('total_diskon') / 100, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($penjualans->sum('total_pajak') / 100, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($penjualans->sum('grand_total') / 100, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Ringkasan Detail Barang -->
    <div style="margin-top: 20px;">
        <div class="report-title" style="font-size: 14px;">RINGKASAN BARANG TERJUAL</div>
        <table border="1">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode Barang</th>
                    <th width="25%">Nama Barang</th>
                    <th width="10%">Satuan</th>
                    <th width="10%">Jumlah</th>
                    <th width="15%">Harga Satuan</th>
                    <th width="10%">Diskon</th>
                    <th width="10%">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Menggabungkan semua detail penjualan
                    $detailSummary = [];

                    foreach ($penjualans as $penjualan) {
                        foreach ($penjualan->details as $detail) {
                            $key = $detail->barang_id . '_' . $detail->nama_barang;

                            if (!isset($detailSummary[$key])) {
                                $detailSummary[$key] = [
                                    'barang_id' => $detail->barang_id,
                                    'nama_barang' => $detail->nama_barang,
                                    'satuan_id' => $detail->satuan_id,
                                    'satuan' => $detail->satuan->nama ?? '-',
                                    'harga_satuan' => $detail->harga_satuan,
                                    'kuantitas' => 0,
                                    'total_diskon' => 0,
                                    'subtotal' => 0,
                                ];
                            }

                            $detailSummary[$key]['kuantitas'] += $detail->kuantitas;
                            $detailSummary[$key]['total_diskon'] += $detail->total_diskon;
                            $detailSummary[$key]['subtotal'] += $detail->subtotal;
                        }
                    }

                    // Konversi ke collection jika diperlukan setelah semua perhitungan selesai
                    $detailSummaryCollection = collect($detailSummary);
                @endphp

                @forelse($detailSummary as $index => $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail['barang_id'] }}</td>
                        <td>{{ $detail['nama_barang'] }}</td>
                        <td>{{ $detail['satuan'] }}</td>
                        <td class="text-right">{{ $detail['kuantitas'] }}</td>
                        <td class="text-right">{{ number_format($detail['harga_satuan'] / 100, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail['total_diskon'] / 100, 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($detail['subtotal'] / 100, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada detail barang yang ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="4">Total</td>
                    <td class="text-right">{{ $total_barang }}</td>
                    <td></td>
                    <td class="text-right">{{ number_format($total_diskon / 100, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($total_penjualan / 100, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Tanda Tangan -->
    <div style="margin-top: 20px;">
        <table border="0" width="100%">
            <tr>
                <td width="60%"></td>
                <td width="40%" style="text-align: center;">
                    Jakarta, {{ date('d F Y') }}
                    <br><br><br><br>
                    (___________________)
                    <br>
                    Manager Penjualan
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan Penjualan - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
