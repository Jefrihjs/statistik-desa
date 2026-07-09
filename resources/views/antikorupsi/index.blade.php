<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Indikator Desa Antikorupsi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #ffffff;
            color: #1f2933;
        }

        .container-antikorupsi {
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        /* HEADER */
        .antikorupsi-header {
            background: linear-gradient(135deg, #475569, #064e3b);
            color: white;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .antikorupsi-header h1 {
            font-size: 28px;
            font-weight: 900;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .antikorupsi-header p {
            font-size: 14px;
            opacity: 0.9;
            margin: 0;
        }

        /* TABS */
        .nav-tabs {
            border: 1px solid #cfd8e3 !important;
            border-bottom: none !important;
            background: #eef0f3;
            border-radius: 0;
            padding: 0;
            gap: 0;
            overflow-x: auto;
            flex-wrap: nowrap;
        }

        .nav-tabs .nav-link {
            color: #111827;
            border: none !important;
            border-radius: 0 !important;
            padding: 16px 18px;
            font-weight: 500;
            font-size: 16px;
            text-transform: none;
            letter-spacing: 0;
            transition: background-color 0.2s ease, color 0.2s ease;
            margin-bottom: 0;
            white-space: nowrap;
        }

        .nav-tabs .nav-link:hover {
            color: #0f5dbd;
            background-color: #e4e8ee;
        }

        .nav-tabs .nav-link.active {
            color: white;
            background-color: #0f61bf !important;
            border-radius: 0;
        }

        /* TAB CONTENT */
        .tab-content {
            background: white;
            border: 1px solid #cfd8e3;
            border-radius: 0;
            padding: 12px;
            box-shadow: none;
        }

        /* ACCORDION */
        .accordion-item {
            border: 1px solid #b9d4f5;
            border-radius: 0;
            margin-bottom: 10px;
            background: white;
            overflow: hidden;
            box-shadow: none;
        }

        .accordion-button {
            background-color: #cfe1fb;
            border-bottom: 1px solid #b9d4f5;
            font-weight: 700;
            font-size: 15px;
            color: #000;
            padding: 16px 20px;
            text-transform: none;
            letter-spacing: 0;
            line-height: 1.3;
        }

        .accordion-button:not(.collapsed) {
            background-color: #cfe1fb;
            color: #000;
            box-shadow: none;
        }

        .accordion-button:hover {
            background-color: #c6dcfa;
        }

        .accordion-button::after {
            background-image: none;
            content: "+";
            width: auto;
            height: auto;
            font-size: 22px;
            line-height: 1;
            transform: none;
            color: #334155;
            font-weight: 500;
        }

        .accordion-button:not(.collapsed)::after {
            content: "-";
            background-image: none;
        }

        /* TABLE */
        .indikator-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin: 0;
            table-layout: fixed;
        }

        .indikator-table tbody tr {
            border-bottom: none;
        }

        .indikator-table tbody tr:nth-child(odd) {
            background-color: #f1f1f1;
        }

        .indikator-table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }

        .indikator-table tbody tr:hover {
            background-color: #eaf2ff;
        }

        .indikator-table tbody tr:last-child {
            border-bottom: none;
        }

        .indikator-table td {
            padding: 10px 16px;
            vertical-align: middle;
            font-size: 15px;
            line-height: 1.5;
        }

        /* NOMOR COLUMN */
        .col-nomor {
            width: 70px;
            text-align: center;
            font-weight: 700;
            color: #1f2933;
            font-size: 15px;
        }

        .nomor-badge {
            display: inline;
            background: transparent;
            padding: 0;
            border-radius: 0;
            font-weight: 700;
            font-size: 15px;
            border-left: none;
        }

        /* SUB COLUMN */
        .col-sub {
            width: 54px;
            text-align: center;
            font-weight: 600;
            color: #1f2933;
            font-size: 15px;
        }

        /* NAMA DOKUMEN COLUMN */
        .col-nama {
            width: auto;
            font-weight: 500;
            color: #1f2933;
        }

        .nama-dokumen {
            line-height: 1.6;
        }

        /* LINK COLUMN */
        .col-link {
            width: 110px;
            text-align: center;
        }

        .btn-lihat {
            background-color: #0f61bf;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .btn-lihat:hover {
            background-color: #0b4d99;
            color: white;
            transform: none;
            box-shadow: none;
        }

        .badge-kosong {
            background-color: #e0e0e0;
            color: #666;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* SUB JUDUL / KATEGORI HEADER */
        .sub-judul-row {
            background: #d7cfcf !important;
            border-left: none;
        }

        .sub-judul-row td {
            color: #000;
            font-weight: 700;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0;
            padding: 10px 12px;
        }

        .hierarchy-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
            background: #f8fafc !important;
        }

        .empty-state-icon {
            font-size: 36px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 16px;
            margin: 0;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .antikorupsi-header h1 {
                font-size: 20px;
            }

            .nav-tabs .nav-link {
                padding: 12px 14px;
                font-size: 14px;
            }

            .tab-content {
                padding: 8px;
            }

            .col-nomor { width: 52px; }
            .col-sub { width: 38px; }
            .col-link { width: 82px; }

            .indikator-table td {
                padding: 9px 8px;
                font-size: 13px;
            }

            .btn-lihat {
                padding: 4px 12px;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

    <div class="container-antikorupsi">
        
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-tatalaksana" data-bs-toggle="tab" data-bs-target="#content-tatalaksana" type="button" role="tab">
                    Tata Laksana
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-pengawasan" data-bs-toggle="tab" data-bs-target="#content-pengawasan" type="button" role="tab">
                    Pengawasan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-pelayanan" data-bs-toggle="tab" data-bs-target="#content-pelayanan" type="button" role="tab">
                    Kualitas Pelayanan Publik
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-partisipasi" data-bs-toggle="tab" data-bs-target="#content-partisipasi" type="button" role="tab">
                    Partisipasi Masyarakat
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-kearifan" data-bs-toggle="tab" data-bs-target="#content-kearifan" type="button" role="tab">
                    Kearifan Lokal
                </button>
            </li>
        </ul>

        
        <div class="tab-content">
            @php
                $kategoriList = [
                    'tatalaksana' => 'tatalaksana',
                    'pengawasan' => 'pengawasan',
                    'pelayanan' => 'pelayanan',
                    'partisipasi' => 'partisipasi',
                    'kearifan' => 'kearifan'
                ];
            @endphp

            @foreach($kategoriList as $key => $kategori)
            <div class="tab-pane fade @if($key === 'tatalaksana') show active @endif" id="content-{{ $kategori }}" role="tabpanel">
                
                @forelse($data[$kategori] ?? [] as $grup => $items)
                    @php
                        $collapseId = 'collapse-' . $kategori . '-' . \Illuminate\Support\Str::slug($grup);
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $grup }}
                            </button>
                        </h2>
                        
                        <div id="{{ $collapseId }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                            <div class="accordion-body p-0">
                                <table class="indikator-table">
                                    <tbody>
                                        @php $lastSubJudul = null; @endphp
                                        @forelse($items as $item)
                                            @if(is_object($item))
                                                @php
                                                    $level = (int) ($item->level ?? 0);
                                                    $indent = $level * 20;
                                                    $isSubJudulOnly = !empty($item->sub_judul) && empty($item->nama_dokumen);
                                                    $isSubJudulHeader = $isSubJudulOnly && empty($item->link_drive);
                                                    $hasSubJudulSection = !empty($item->sub_judul);
                                                    $displayName = $item->nama_dokumen ?: $item->sub_judul;
                                                    $displayNoUrut = $item->no_urut;
                                                    $displaySub = $item->sub;

                                                    if (!$displaySub && $level === 1 && !empty($item->no_urut) && str_contains($item->no_urut, '.')) {
                                                        [$displayNoUrut, $legacyChildNumber] = array_pad(explode('.', $item->no_urut, 2), 2, null);

                                                        if (is_numeric($legacyChildNumber)) {
                                                            $displaySub = chr(96 + max(1, min((int) $legacyChildNumber, 26)));
                                                        }
                                                    }
                                                @endphp

                                                {{-- SUB JUDUL / KATEGORI HEADER --}}
                                                @if($isSubJudulHeader)
                                                    <tr class="sub-judul-row">
                                                        <td colspan="4">
                                                            <div style="margin-left: {{ $indent }}px;">{{ $item->sub_judul }}</div>
                                                        </td>
                                                    </tr>
                                                    @php $lastSubJudul = $item->sub_judul; @endphp
                                                @else
                                                    @if(!$isSubJudulOnly && $hasSubJudulSection && $item->sub_judul !== $lastSubJudul)
                                                        <tr class="sub-judul-row">
                                                            <td colspan="4">
                                                                <div style="margin-left: {{ $indent }}px;">{{ $item->sub_judul }}</div>
                                                            </td>
                                                        </tr>
                                                        @php $lastSubJudul = $item->sub_judul; @endphp
                                                    @endif

                                                    {{-- INDIKATOR/DOKUMEN --}}
                                                    <tr>
                                                        <td class="col-nomor">
                                                            @if(!empty($displayNoUrut))
                                                                <span class="nomor-badge">{{ $displayNoUrut }}</span>
                                                            @endif
                                                        </td>
                                                        
                                                        <td class="col-sub">
                                                            @if(!empty($displaySub))
                                                                <strong>{{ $displaySub }}</strong>
                                                            @endif
                                                        </td>
                                                        
                                                        <td class="col-nama">
                                                            <div class="nama-dokumen" style="margin-left: {{ $indent }}px;">
                                                                {{ $displayName }}
                                                            </div>
                                                        </td>
                                                        
                                                        <td class="col-link">
                                                            @if(!empty($item->link_drive))
                                                                <a href="{{ $item->link_drive }}" class="btn-lihat" target="_blank" rel="noopener noreferrer">
                                                                    Lihat
                                                                </a>
                                                            @else
                                                                <span class="badge-kosong">Kosong</span>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                @endif

                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="4" class="empty-state">
                                                    <div class="empty-state-icon">📄</div>
                                                    <p>Tidak ada indikator dalam grup ini</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">📂</div>
                        <p>Belum ada data indikator untuk kategori ini</p>
                    </div>
                @endforelse

            </div>
            @endforeach

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
