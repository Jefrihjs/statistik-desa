<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lacak Permohonan</title>
    <style>
        body {
            margin: 0;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #0f172a;
        }

        .wrap {
            max-width: 560px;
            margin: 0 auto;
            padding: 60px 20px;
            text-align: center;
        }

        .icon {
            width: 68px;
            height: 68px;
            background: #fef3c7;
            color: #d97706;
            border-radius: 22px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 26px;
            font-size: 34px;
        }

        h1 {
            font-size: 30px;
            margin: 0;
            font-weight: 900;
        }

        h1 span {
            color: #2563eb;
        }

        .sub {
            margin-top: 14px;
            color: #64748b;
            line-height: 1.7;
            font-size: 14px;
        }

        .card {
            margin-top: 40px;
            background: white;
            border-radius: 34px;
            padding: 40px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
            text-align: left;
        }

        label {
            display: block;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 18px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            font-size: 18px;
            font-weight: 900;
            text-align: center;
            letter-spacing: .35em;
            text-transform: uppercase;
        }

        .field {
            margin-bottom: 28px;
        }

        .nik {
            letter-spacing: normal;
            text-align: left;
            font-size: 15px;
        }

        button {
            width: 100%;
            background: #0f172a;
            color: white;
            border: 0;
            padding: 20px;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 900;
            cursor: pointer;
        }

        .error {
            background: #fff1f2;
            color: #be123c;
            padding: 14px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .note {
            text-align: center;
            color: #94a3b8;
            margin-top: 30px;
            font-size: 12px;
            font-style: italic;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="icon">⌕</div>

        <h1>Lacak <span>Permohonan</span></h1>

        <p class="sub">
            Masukkan kode permohonan 7 karakter Anda untuk melihat status pemrosesan secara real-time.
        </p>

        <div class="card">
            @if($errors->any())
                <div class="error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('embed.ppid.permohonan.monitoring.cek', $desa->slug) }}" method="POST">
                @csrf

                <div class="field">
                    <label>Kode Permohonan</label>
                    <input type="text"
                           name="kode_permohonan"
                           maxlength="7"
                           value="{{ old('kode_permohonan') }}"
                           required>
                </div>

                <div class="field">
                    <label>NIK (16 Digit)</label>
                    <input type="text"
                           name="nik"
                           maxlength="16"
                           class="nik"
                           value="{{ old('nik') }}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           required>
                </div>

                <button type="submit">
                    Lacak Status →
                </button>
            </form>
        </div>

        <p class="note">
            Lupa kode permohonan? Silakan cek bukti pendaftaran Anda atau hubungi petugas PPID Desa.
        </p>
    </div>
</body>
</html>