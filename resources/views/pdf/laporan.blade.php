<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; }

    .header { padding: 20px 24px 16px; border-bottom: 2px solid #2563eb; display: flex; justify-content: space-between; align-items: flex-start; }
    .header-left h1 { font-size: 16px; font-weight: 700; color: #0f172a; }
    .header-left p  { font-size: 9px; color: #64748b; margin-top: 2px; }
    .header-right   { text-align: right; }
    .header-right .period { font-size: 12px; font-weight: 700; color: #2563eb; }
    .header-right small   { font-size: 8px; color: #94a3b8; }

    .stats { display: flex; padding: 12px 24px; gap: 0; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .stat-box { flex: 1; text-align: center; padding: 8px; border-right: 1px solid #e2e8f0; }
    .stat-box:last-child { border-right: none; }
    .stat-box .val { font-size: 20px; font-weight: 700; color: #0f172a; line-height: 1; }
    .stat-box .lbl { font-size: 8px; color: #64748b; margin-top: 3px; text-transform: uppercase; letter-spacing: .05em; }

    .section-title { padding: 12px 24px 6px; font-size: 9px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .1em; }

    table { width: 100%; border-collapse: collapse; }
    thead th { background: #f1f5f9; padding: 8px 10px; font-size: 8px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: .04em; text-align: left; }
    thead th:first-child { padding-left: 24px; }
    thead th:last-child  { padding-right: 24px; }
    tbody td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 9px; vertical-align: middle; }
    tbody td:first-child { padding-left: 24px; }
    tbody td:last-child  { padding-right: 24px; }
    tbody tr:last-child td { border-bottom: none; }

    .badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 8px; font-weight: 600; }
    .badge-menunggu     { background: #fef3c7; color: #92400e; }
    .badge-dikonfirmasi { background: #dbeafe; color: #1e40af; }
    .badge-selesai      { background: #dcfce7; color: #14532d; }
    .badge-dibatalkan   { background: #fee2e2; color: #7f1d1d; }

    .footer { margin-top: 20px; padding: 12px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; }
    .footer p { font-size: 8px; color: #94a3b8; }
</style>
</head>
<body>

<div class="header">
    <div class="header-left">
        <h1>&#x2302; Klinik Central Medika</h1>
        <p>Laporan Bulanan Appointment Pasien</p>
    </div>
    <div class="header-right">
        <div class="period">{{ $namaBulan }} {{ $tahun }}</div>
        <small>Dicetak: {{ now()->timezone('Asia/Jakarta')->isoFormat('D MMMM YYYY, HH:mm') }} WIB</small>
    </div>
</div>

<div class="stats">
    <div class="stat-box">
        <div class="val">{{ $statistik['total'] }}</div>
        <div class="lbl">Total</div>
    </div>
    <div class="stat-box">
        <div class="val" style="color:#16a34a">{{ $statistik['selesai'] }}</div>
        <div class="lbl">Selesai</div>
    </div>
    <div class="stat-box">
        <div class="val" style="color:#dc2626">{{ $statistik['dibatalkan'] }}</div>
        <div class="lbl">Dibatalkan</div>
    </div>
    <div class="stat-box">
        <div class="val" style="color:#2563eb">
            {{ $statistik['total'] > 0 ? round(($statistik['selesai'] / $statistik['total']) * 100) : 0 }}%
        </div>
        <div class="lbl">Tingkat Selesai</div>
    </div>
</div>

<div class="section-title">Detail Appointment</div>

<table>
    <thead>
        <tr>
            <th style="width:5%">No</th>
            <th style="width:20%">Pasien</th>
            <th style="width:8%">No. RM</th>
            <th style="width:22%">Dokter</th>
            <th style="width:14%">Tanggal</th>
            <th style="width:20%">Keluhan</th>
            <th style="width:11%">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $i => $apt)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $apt->pasien->user->name }}</td>
            <td><span style="font-family:monospace;font-size:8px">{{ $apt->pasien->no_rm }}</span></td>
            <td>
                {{ $apt->dokter->user->name }}<br>
                <span style="color:#64748b;font-size:8px">{{ $apt->dokter->spesialisasi }}</span>
            </td>
            <td>{{ $apt->tanggal_appointment->isoFormat('D MMM YYYY') }}</td>
            <td style="color:#475569">{{ Str::limit($apt->keluhan, 50) }}</td>
            <td>
                <span class="badge badge-{{ $apt->status }}">{{ ucfirst($apt->status) }}</span>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:20px;color:#94a3b8">
                Tidak ada data appointment pada periode ini
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <p>Laporan ini dibuat otomatis oleh Sistem Manajemen Klinik Central Medika</p>
    <p>Halaman 1</p>
</div>

</body>
</html>
