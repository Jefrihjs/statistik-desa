<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Permohonan Berhasil</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #0f172a;
        }

        .wrap {
            max-width: 760px;
            margin: 0 auto;
            padding: 50px 20px;
            text-align: center;
        }

        .icon {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            background: #dcfce7;
            color: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin: 0 auto 28px;
            font-weight: 900;
        }

        h1 {
            font-size: 30px;
            margin: 0 0 22px;
            font-weight: 900;
        }

        .kode-box {
            position: relative;
            background: white;
            border: 1px dashed #cbd5e1;
            border-radius: 28px;
            padding: 28px;
            margin: 0 auto 30px;
            max-width: 560px;
        }

        .badge {
            position: absolute;
            right: 24px;
            top: -14px;
            background: #2563eb;
            color: white;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .label {
            font-size: 12px;
            color: #64748b;
            font-weight: 900;
            letter-spacing: .2em;
            text-transform: uppercase;
        }

        .kode {
            margin-top: 10px;
            font-size: 36px;
            font-weight: 900;
            color: #2563eb;
            letter-spacing: .18em;
        }

        p {
            color: #334155;
            font-size: 14px;
            line-height: 1.7;
        }

        .survey {
            margin: 40px auto;
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            border-radius: 28px;
            padding: 28px;
            text-align: left;
            max-width: 560px;
        }

        .survey h2 {
            margin: 0 0 10px;
            font-size: 18px;
            font-weight: 900;
        }

        .btns {
            display: flex;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-top: 34px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 16px 28px;
            border-radius: 14px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .btn-blue {
            background: #2563eb;
            color: white;
        }

        .btn-white {
            background: white;
            color: #0f172a;
            border: 1px solid #0f172a;
        }

        .survey-btn {
            display: inline-flex;
            margin-top: 18px;
            padding: 12px 20px;
            border-radius: 14px;
            background: #2563eb;
            color: #ffffff;
            text-decoration: none;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .survey-btn:hover {
            background: #1d4ed8;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="icon">✓</div>

        <h1>Permohonan Berhasil Disampaikan</h1>

        <div class="kode-box">
            <div class="badge">Kode Monitoring</div>
            <div class="label">Kode Permohonan:</div>
            <div class="kode">{{ strtoupper($permohonan->kode_permohonan) }}</div>
        </div>

        <p>
            Mohon dicatat / disimpan dengan baik <strong>kode permohonan</strong> ini untuk melakukan proses monitoring
            ataupun pengajuan keberatan terhadap permohonan yang telah disampaikan.
        </p>

        <p>
            Anda juga dapat melakukan unduh bukti pengajuan permohonan melalui tautan di bawah ini.
        </p>

        <div class="survey">
            <h2>Bantu Kami Menjadi Lebih Baik!</h2>
            <p>
                Mohon kesediaan Anda mengisi survei kepuasan masyarakat untuk meningkatkan kualitas layanan informasi publik.
            </p>

            <a href="{{ route('public.skm.create', $desa->slug) }}"
            target="_blank"
            style="display:inline-flex;margin-top:18px;padding:12px 20px;border-radius:14px;background:linear-gradient(135deg, {{ $desa->header_color ?? '#2563eb' }}, {{ $desa->accent_color ?? '#0f766e' }});color:#fff;text-decoration:none;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em;">
                Isi Survei Kepuasan Masyarakat
            </a>
        </div>

        <div class="btns">
            <a href="{{ route('embed.ppid.permohonan.monitoring', $desa->slug) }}" class="btn btn-blue">
                Monitoring Status
            </a>

            <a href="{{ route('embed.ppid.permohonan.bukti', [$desa->slug, $permohonan->kode_permohonan]) }}"
            target="_blank"
            class="btn btn-white">
                Unduh Bukti
            </a>
        </div>
    </div>
</body>
</html>