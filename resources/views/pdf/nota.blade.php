<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pengambilan Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header table { w-full; border: none; }
        .header td { border: none; padding: 0; }
        .title { font-size: 24px; font-weight: bold; margin: 0; }
        .subtitle { font-size: 11px; color: #666; margin: 0; }
        .nota-title { font-size: 18px; font-weight: bold; text-align: right; text-transform: uppercase; margin: 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 2px 0; vertical-align: top; }
        .info-label { width: 100px; color: #666; font-size: 11px; text-transform: uppercase; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #eee; text-align: left; padding: 8px; border: 1px solid #ddd; }
        .items-table td { padding: 8px; border: 1px solid #ddd; }
        .ttd-table { width: 100%; margin-top: 50px; text-align: center; }
        .ttd-table td { width: 33%; }
        .footer { margin-top: 30px; border-top: 1px dashed #ccc; padding-top: 10px; font-size: 10px; text-align: center; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%">
            <tr>
                <td>
                    <h1 class="title">GUDANGKITA</h1>
                    <p class="subtitle">Sistem Manajemen Inventory & Aset</p>
                </td>
                <td style="text-align: right">
                    <h2 class="nota-title">Nota Pengambilan</h2>
                    <p style="margin: 5px 0 0 0; font-family: monospace; font-size: 14px;">{{ $nota->no_transaksi }}</p>
                    <p style="margin: 0; font-size: 10px; color: #666;">Dicetak: {{ \Carbon\Carbon::parse($nota->waktu_cetak)->format('d/m/Y H:i') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 50%">
                <table>
                    <tr><td colspan="3" style="font-weight: bold; padding-bottom: 5px;">Informasi Request</td></tr>
                    <tr><td class="info-label">No. Request</td><td style="width: 10px;">:</td><td>{{ $requestBarang->no_transaksi }}</td></tr>
                    <tr><td class="info-label">Tanggal</td><td>:</td><td>{{ \Carbon\Carbon::parse($requestBarang->tanggal)->format('d/m/Y') }}</td></tr>
                    <tr><td class="info-label">Keperluan</td><td>:</td><td>{{ $requestBarang->keperluan }}</td></tr>
                </table>
            </td>
            <td style="width: 50%">
                <table>
                    <tr><td colspan="3" style="font-weight: bold; padding-bottom: 5px;">Peminta</td></tr>
                    <tr><td class="info-label">Nama</td><td style="width: 10px;">:</td><td>{{ optional($requestBarang->peminta)->nama_lengkap }}</td></tr>
                    <tr><td class="info-label">Departemen</td><td>:</td><td>{{ optional($requestBarang->departemen)->nama }}</td></tr>
                    <tr><td class="info-label">Lokasi</td><td>:</td><td>{{ optional($requestBarang->gedung)->nama }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 15%;">Kode Barang</th>
                <th style="width: 45%;">Nama Barang</th>
                <th style="width: 15%; text-align: center;">Qty</th>
                <th style="width: 20%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requestBarang->details as $i => $detail)
            <tr>
                <td style="text-align: center;">{{ $i+1 }}</td>
                <td style="font-family: monospace;">{{ optional($detail->barang)->kode_barang }}</td>
                <td>{{ optional($detail->barang)->nama }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $detail->qty }} {{ optional($detail->barang)->satuan }}</td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="ttd-table">
        <tr>
            <td>Peminta / Penerima,<br><br><br><br><br><b><u>{{ optional($requestBarang->peminta)->nama_lengkap }}</u></b><br>Tgl: .....................</td>
            <td>Mengetahui (Admin),<br><br><br><br><br><b><u>{{ optional($requestBarang->approver)->nama_lengkap ?? '.........................' }}</u></b><br>Tgl: .....................</td>
            <td>Petugas Gudang,<br><br><br><br><br><b><u>.........................</u></b><br>Tgl: .....................</td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dicetak dari sistem GudangKita secara otomatis. Harap simpan sebagai bukti transaksi yang sah.
    </div>
</body>
</html>
