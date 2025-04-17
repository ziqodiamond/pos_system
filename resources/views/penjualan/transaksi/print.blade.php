<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi {{ $transaksi->nomor }}</title>
    <style>
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            padding: 20px;
            font-size: 12px;
            line-height: 1.5;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            margin: 0;
        }

        /* Info transaksi */
        .transaction-info {
            margin-bottom: 20px;
            width: 100%;
        }

        .transaction-info td {
            padding: 3px 0;
        }

        .transaction-info .label {
            width: 120px;
        }

        /* Detail transaksi */
        .transaction-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .transaction-details th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .transaction-details td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .transaction-details .number {
            text-align: right;
        }

        /* Total */
        .total-section {
            width: 100%;
            margin-top: 10px;
        }

        .total-section table {
            width: 350px;
            float: right;
        }

        .total-section td {
            padding: 5px 0;
        }

        .total-section .label {
            text-align: left;
        }

        .total-section .value {
            text-align: right;
        }

        .total-section .grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }

        /* Clearfix */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $toko['nama'] }}</h1>
        <p>{{ $toko['alamat'] }}</p>
        <p>Telp: {{ $toko['telepon'] }} | Email: {{ $toko['email'] }}</p>
        @if ($toko['npwp'])
            <p>NPWP: {{ $toko['npwp'] }}</p>
        @endif
    </div>

    <table class="transaction-info">
        <tr>
            <td class="label">No. Transaksi</td>
            <td>: {{ $transaksi->no_ref }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($transaksi->created_at)->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Kasir</td>
            <td>: {{ $transaksi->user ? $transaksi->user->name : 'Admin' }}</td>
        </tr>
        @if ($transaksi->customer)
            <tr>
                <td class="label">Pelanggan</td>
                <td>: {{ $transaksi->customer->nama }}</td>
            </tr>
            @if ($transaksi->customer->telepon)
                <tr>
                    <td class="label">Telepon</td>
                    <td>: {{ $transaksi->customer->telepon }}</td>
                </tr>
            @endif
        @endif
    </table>

    <table class="transaction-details">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 40%">Item</th>
                <th style="width: 15%">Harga</th>
                <th style="width: 10%">Qty</th>
                <th style="width: 15%">Diskon</th>
                <th style="width: 15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->details as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->nama_barang }}</td>
                    <td class="number">{{ number_format($detail->harga_satuan / 100, 2, ',', '.') }}</td>
                    <td class="number">{{ $detail->kuantitas }} {{ $detail->barang->satuan->nama ?? 'pcs' }}</td>
                    <td class="number">
                        @if ($detail->diskon_nominal > 0)
                            {{ number_format($detail->diskon_nominal / 100, 2, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="number">{{ number_format($detail->total / 100, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="clearfix">
        <div class="total-section">
            <table>
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">{{ number_format($transaksi->subtotal / 100, 2, ',', '.') }}</td>
                </tr>
                @if ($transaksi->total_diskon > 0)
                    <tr>
                        <td class="label">Diskon</td>
                        <td class="value">{{ number_format($transaksi->total_diskon / 100, 2, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($transaksi->total_pajak > 0)
                    <tr>
                        <td class="label">Pajak</td>
                        <td class="value">{{ number_format($transaksi->total_pajak / 100, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">DPP</td>
                        <td class="value">{{ number_format($transaksi->dpp / 100, 2, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="label grand-total">TOTAL</td>
                    <td class="value grand-total">{{ number_format($transaksi->grand_total / 100, 2, ',', '.') }}</td>
                </tr>
                @if ($transaksi->metode_pembayaran === 'tunai' && $transaksi->total_bayar > 0)
                    <tr>
                        <td class="label">Tunai</td>
                        <td class="value">{{ number_format($transaksi->total_bayar / 100, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kembalian</td>
                        <td class="value">{{ number_format($transaksi->kembalian / 100, 2, ',', '.') }}</td>
                    </tr>
                @else
                    <tr>
                        <td class="label">Pembayaran</td>
                        <td class="value">{{ ucfirst($transaksi->metode_pembayaran) }}</td>
                    </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih telah berbelanja di toko kami</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
    </div>
</body>

</html>
