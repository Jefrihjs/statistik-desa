<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    
    <title>Indikator Desa Antikorupsi</title>

    <style>
        img { display: block; margin-left: auto; margin-right: auto; }
        /* Custom style Bapak */
        #flush-collapseOne .accordion-button:hover::after {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='%23333' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' d='M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z' clip-rule='evenodd'/%3e%3c/svg%3e");
            transform: scale(0.7) translateX(0.2rem);
            background-color: #e01191;
        }
        .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='%23333' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' d='M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z' clip-rule='evenodd'/%3e%3c/svg%3e");
            transform: scale(.7) !important;
        }
        .accordion-button:not(.collapsed)::after {
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='%23333' xmlns='http://www.w3.org/2000/svg'%3e%3cpath fill-rule='evenodd' d='M0 8a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H1a1 1 0 0 1-1-1z' clip-rule='evenodd'/%3e%3c/svg%3e");
        }
        table { border-collapse: collapse; width: 100%; }
        td, th { text-align: left; padding: 10px; } /* Padding dirapikan sedikit agar tidak terlalu mepet */
        tr:nth-child(even) { background-color: #e4dbdb; }
        
        .tab { overflow: hidden; border: 1px solid #ccc; background-color: #f1f1f1; }
        .tab button { background-color: inherit; float: left; border: none; outline: none; cursor: pointer; padding: 14px 16px; transition: 0.3s; font-size: 17px; }
        .tab button:hover { background-color: #1560bd; color: #ffffff; }
        .tab button.active { background-color: #1560bd; color: #ffffff; }
        
        .accordion-button:hover { background-color: #cfe2ff; color: #000000; }
        .accordion-button:not(.collapsed) { background-color: #cfe2ff; color: #000000; }
        .tabcontent { display: none; padding: 15px; border: 1px solid #be9e9e; border-top: none; }
    </style>
</head>
<body>

    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'tatalaksana')" id="defaultOpen">Tata Laksana</button>
        <button class="tablinks" onclick="openTab(event, 'pengawasan')">Pengawasan</button>
        <button class="tablinks" onclick="openTab(event, 'pelayanan')">Kualitas Pelayanan Publik</button>
        <button class="tablinks" onclick="openTab(event, 'partisipasi')">Partisipasi Masyarakat</button>
        <button class="tablinks" onclick="openTab(event, 'kearifan')">Kearifan Lokal</button>
    </div>

    @php
        // Array kategori untuk dilooping sesuai urutan Tab
        $kategoriList = ['tatalaksana', 'pengawasan', 'pelayanan', 'partisipasi', 'kearifan'];
        $globalCounter = 1; // Untuk ID Accordion Bootstrap agar unik
    @endphp

    @foreach($kategoriList as $kat)
    <div id="{{ $kat }}" class="tabcontent">
        <div class="accordion accordion-flush" id="accordionFlush-{{ $kat }}">
            
            @forelse($data[$kat] ?? [] as $grup => $items)
            <div class="accordion-item border border-secondary-subtle mb-2 rounded">
                
                <h2 class="accordion-header" id="heading-{{ $globalCounter }}">
                    <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{ $globalCounter }}" aria-expanded="false" aria-controls="collapse-{{ $globalCounter }}">
                        {{ $grup }}
                    </button>
                </h2>
                
                <div id="collapse-{{ $globalCounter }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $globalCounter }}" data-bs-parent="#accordionFlush-{{ $kat }}">
                    <div class="accordion-body p-0">
                        <table class="table table-borderless table-striped m-0">
                            <tbody>
                                @foreach($items as $item)
                                    @if(is_object($item))

                                        {{-- BARIS SUB KATEGORI / SUB JUDUL --}}
                                        @if(!empty($item->sub_judul) && empty($item->nama_dokumen))
                                            <tr>
                                                <td colspan="4" style="background-color:#e4dbdb; color:#000; font-weight:700; padding:10px;">
                                                    {{ $item->sub_judul }}
                                                </td>
                                            </tr>
                                        @else

                                            {{-- BARIS DOKUMEN BIASA --}}
                                            <tr>
                                                <td width="5%" class="text-center fw-bold">
                                                    {{ $item->no_urut ?? '' }}
                                                </td>

                                                <td width="5%" class="text-center">
                                                    {{ $item->sub ?? '' }}
                                                </td>

                                                <td>
                                                    {{ $item->nama_dokumen }}
                                                </td>

                                                <td width="15%" class="text-center">
                                                    @if(!empty($item->link_drive))
                                                        <a href="{{ $item->link_drive }}" class="btn btn-sm text-white" style="background-color:#1560bd;" target="_blank">
                                                            Lihat
                                                        </a>
                                                    @else
                                                        <span class="badge bg-secondary">Kosong</span>
                                                    @endif
                                                </td>
                                            </tr>

                                        @endif

                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @php $globalCounter++; @endphp
            @empty
                <div class="alert alert-secondary m-3 text-center">
                    Belum ada data indikator untuk kategori ini.
                </div>
            @endforelse
            
        </div>
    </div>
    @endforeach

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        // Buka tab pertama secara default
        document.getElementById("defaultOpen").click();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>

</body>
</html>