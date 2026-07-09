<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Informasi - {{ $desa->nama_desa }}</title>

    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        .wrap {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .title {
            margin: 0;
            font-size: 28px;
            font-weight: 900;
            color: #1e3a8a;
            text-transform: uppercase;
        }

        .subtitle {
            margin-top: 8px;
            color: #64748b;
            font-size: 14px;
        }

        .section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 24px;
            padding: 24px;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 900;
            margin-bottom: 20px;
            color: #0f172a;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #334155;
        }

        input, select, textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 12px 14px;
            font-size: 14px;
            outline: none;
            background: #f8fafc;
        }

        textarea {
            resize: vertical;
        }

        .notice {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            border-radius: 18px;
            padding: 16px;
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            border-radius: 18px;
            padding: 16px;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #be123c;
            border-radius: 18px;
            padding: 16px;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .option-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .option {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px;
            font-size: 13px;
            font-weight: 700;
        }

        .option input {
            width: auto;
        }

        .submit {
            width: 100%;
            border: 0;
            background: #0f172a;
            color: white;
            padding: 16px 20px;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 900;
            cursor: pointer;
            text-transform: uppercase;
        }

        .submit:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }

        @media (max-width: 700px) {
            body {
                padding: 12px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .option-grid {
                grid-template-columns: 1fr;
            }

            .title {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
<div class="wrap" x-data="{ kategori: '{{ old('kategori_pemohon') }}', setuju: false, nik: '{{ old('nik') }}', caraKirim: '{{ old('cara_pengiriman') }}' }">

    <div class="header">
        <h1 class="title">Permohonan Informasi</h1>
        <div class="subtitle">
            PPID Desa {{ $desa->nama_desa ?? '-' }}
        </div>
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('embed.ppid.permohonan.store', $desa->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="notice">
            Pemerintah Desa {{ $desa->nama_desa ?? '-' }} menjaga kerahasiaan identitas dan dokumen yang diunggah.
            Data digunakan hanya untuk verifikasi permohonan informasi publik.
        </div>

        <div class="section">
            <div class="section-title">01. Identitas Pemohon</div>

            <div class="grid">
                <div class="full">
                    <label>Kategori Pemohon</label>
                    <select name="kategori_pemohon" x-model="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="perorangan">Perorangan</option>
                        <option value="lembaga">Lembaga / Organisasi</option>
                    </select>
                </div>

                <div>
                    <label>Nomor Identitas / NIK</label>
                    <input type="text" name="nik" x-model="nik" maxlength="16"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           value="{{ old('nik') }}" required>
                </div>

                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required>
                </div>

                <div>
                    <label>Upload KTP</label>
                    <input type="file" name="file_ktp" accept=".jpg,.jpeg,.png" required>
                </div>

                <div x-show="kategori === 'lembaga'">
                    <label>Upload Akta Notaris Lembaga</label>
                    <input type="file" name="file_akta" accept=".jpg,.jpeg,.png">
                </div>

                <div class="full">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}">
                </div>

                <div>
                    <label>Nomor Ponsel / WA</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required>
                </div>

                <div class="full">
                    <label>Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}">
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">02. Data Permohonan</div>

            <div class="grid">
                <div class="full">
                    <label>Rincian Informasi</label>
                    <textarea name="rincian_informasi" rows="3" required>{{ old('rincian_informasi') }}</textarea>
                </div>

                <div class="full">
                    <label>Tujuan Penggunaan Informasi</label>
                    <textarea name="tujuan_penggunaan" rows="2" required>{{ old('tujuan_penggunaan') }}</textarea>
                </div>

                <div>
                    <label>Cara Memperoleh Informasi</label>
                    <div class="option-grid">
                        @foreach(['melihat' => 'Melihat', 'membaca' => 'Membaca', 'mendengar' => 'Mendengar', 'mencatat' => 'Mencatat'] as $value => $label)
                            <label class="option">
                                <input type="radio" name="cara_memperoleh" value="{{ $value }}" {{ old('cara_memperoleh') == $value ? 'checked' : '' }}>
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label>Mendapatkan Salinan Informasi</label>
                    <div class="option-grid">
                        <label class="option">
                            <input type="radio" name="jenis_salinan" value="softcopy" {{ old('jenis_salinan') == 'softcopy' ? 'checked' : '' }}>
                            Softcopy
                        </label>

                        <label class="option">
                            <input type="radio" name="jenis_salinan" value="hardcopy" {{ old('jenis_salinan') == 'hardcopy' ? 'checked' : '' }}>
                            Hardcopy
                        </label>
                    </div>
                </div>

                <div class="full">
                    <label>Cara Mendapatkan Salinan Informasi</label>
                    <select name="cara_pengiriman" x-model="caraKirim">
                        <option value="">-- Pilih Cara Pengiriman --</option>
                        <option value="diambil">Mengambil Langsung</option>
                        <option value="email">Melalui E-Mail</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>

                <div class="full" x-show="caraKirim === 'whatsapp'">
                    <label>Nomor WhatsApp Aktif</label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" placeholder="812xxxxxxx">
                </div>
            </div>
        </div>

        <div class="section">
            <label class="option">
                <input type="checkbox" x-model="setuju">
                Saya menyatakan bahwa data yang saya berikan adalah benar.
            </label>

            <button type="submit"
                    class="submit"
                    :disabled="!setuju || nik.length !== 16">
                Kirim Permohonan
            </button>
        </div>
    </form>
</div>
</body>
</html>