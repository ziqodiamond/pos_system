<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data User</title>
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

        /* Pewarnaan role */
        .role-admin {
            color: blue;
        }

        .role-kasir {
            color: green;
        }

        .role-gudang {
            color: orange;
        }

        .role-super-admin {
            color: purple;
        }
    </style>
</head>

<body>
    <!-- Header Laporan -->
    <div class="header">
        <div class="company-name">{{ $toko['nama'] }}</div>
        <div class="company-address">{{ $toko['alamat'] }}, {{ $toko['kota'] }}, {{ $toko['provinsi'] }}</div>
        <div class="company-address">Telp: {{ $toko['telepon'] }} | Email: {{ $toko['email'] }}</div>
        <div class="report-title">LAPORAN DATA USER</div>
    </div>

    <!-- Informasi Meta -->
    <div class="meta-info">
        <table border="0">
            <tr>
                <td width="120">Tanggal Cetak</td>
                <td>: {{ $tanggal_cetak }}</td>
                <td width="120">Total User</td>
                <td>: {{ $total_items }} user</td>
            </tr>
            <tr>
                <td>Filter</td>
                <td>:
                    @if (isset($filter['status']) && $filter['status'] === 'deleted')
                        Data Terhapus |
                    @endif

                    @if (isset($filter['role']) && !empty($filter['role']))
                        Role: {{ ucfirst($filter['role']) }} |
                    @endif

                    @if (isset($filter['name']) && !empty($filter['name']))
                        Nama: {{ $filter['name'] }} |
                    @endif

                    Urut: {{ ucfirst($filter['sort_by'] ?? 'name') }}
                    ({{ ($filter['sort_order'] ?? 'asc') == 'asc' ? 'A-Z' : 'Z-A' }})
                </td>
                <td>Total Transaksi</td>
                <td>: {{ number_format($total_transaksi, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data User -->
    <table border="1">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="20%">Nama</th>
                <th width="10%">Role</th>
                <th width="15%">Penjualan</th>
                <th width="15%">Pembelian</th>
                <th width="15%">Total Transaksi</th>
                <th width="10%">Last Login</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->kode }}</td>
                    <td>{{ $user->name }}</td>
                    <td class="role-{{ strtolower(str_replace('_', '-', $user->role)) }}">
                        {{ ucfirst($user->role) }}
                    </td>
                    <td class="text-right">{{ $user->penjualan->count() }}</td>
                    <td class="text-right">{{ $user->pembelian->count() }}</td>
                    <td class="text-right">{{ $user->penjualan->count() + $user->pembelian->count() }}</td>
                    <td>{{ $user->last_login_at ? date('d/m/Y H:i', strtotime($user->last_login_at)) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data user yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4">Total</td>
                <td class="text-right">{{ $total_penjualan }}</td>
                <td class="text-right">{{ $total_pembelian }}</td>
                <td class="text-right">{{ $total_transaksi }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- Ringkasan Per Role -->
    @if (count($users) > 0)
        <div style="margin-top: 15px; font-size: 11px;">
            <strong>Ringkasan Per Role:</strong>
            <table border="1" style="margin-top: 5px;">
                <thead>
                    <tr>
                        <th width="20%">Role</th>
                        <th width="20%">Jumlah User</th>
                        <th width="20%">Total Penjualan</th>
                        <th width="20%">Total Pembelian</th>
                        <th width="20%">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $roles = $users->groupBy('role');
                    @endphp

                    @foreach ($roles as $role => $roleUsers)
                        @php
                            $rolePenjualan = $roleUsers->sum(function ($user) {
                                return $user->penjualan->count();
                            });
                            $rolePembelian = $roleUsers->sum(function ($user) {
                                return $user->pembelian->count();
                            });
                        @endphp
                        <tr>
                            <td class="role-{{ strtolower(str_replace('_', '-', $role)) }}">{{ ucfirst($role) }}</td>
                            <td class="text-right">{{ $roleUsers->count() }}</td>
                            <td class="text-right">{{ $rolePenjualan }}</td>
                            <td class="text-right">{{ $rolePembelian }}</td>
                            <td class="text-right">{{ $rolePenjualan + $rolePembelian }}</td>
                        </tr>
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
                    Manager HRD
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Laporan Data User - Dicetak pada {{ $tanggal_cetak }}
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>

</html>
