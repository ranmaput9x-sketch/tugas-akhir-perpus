@extends('adminlte::page')

@section('title', 'Daftar Buku')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar Katalog Buku</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list-ul"></i> Daftar Buku</span>
            <div>
                <a href="{{ route('buku.create') }}" class="btn btn-primary btn-sm mr-1">
                    <i class="fas fa-plus"></i> Tambah Buku
                </a>
                <button id="delete-selected-btn" class="btn btn-danger btn-sm" disabled>
                    <i class="fas fa-trash"></i> Hapus Terpilih
                </button>
            </div>
        </div>
        <div class="card-body">
            @php
                use Illuminate\Support\HtmlString;

                // Header kolom dengan icon “Pilih Semua”
                $heads = [
                    [
                        'label' => new HtmlString(
                            '<i id="select-all" class="far fa-square" style="cursor:pointer;font-size:1.2rem;"></i>',
                        ),
                        'no-export' => true,
                        'width' => 5,
                    ],
                    ['label' => 'No', 'width' => 5],
                    ['label' => 'Cover', 'no-export' => true, 'width' => 10],
                    'Judul Buku',
                    'Penulis',
                    'Penerbit',
                    'Nomor Panggil',
                    ['label' => 'Stok', 'width' => 5],
                    ['label' => 'Aksi', 'no-export' => true, 'width' => 15],
                ];

                // Konfigurasi DataTable
                $config = [
                    'data' => [],
                    'order' => [[1, 'asc']],
                    'dom' => '<"d-flex justify-content-between mb-3"<l><f>>rtip',
                    'pageLength' => 10,
                    'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                    'columns' => [
                        ['orderable' => false, 'searchable' => false, 'className' => 'text-center'], // icon select-all
                        null, // No
                        ['orderable' => false, 'searchable' => false], // Cover
                        null, // Judul
                        null, // Penulis
                        null, // Penerbit
                        null, // Nomor Panggil
                        null, // Stok
                        ['orderable' => false, 'searchable' => false, 'className' => 'text-center'], // Aksi
                    ],
                    'language' => [
                        'search' => 'Cari:',
                        'lengthMenu' => 'Tampilkan _MENU_ entri',
                        'zeroRecords' => 'Tidak ada data yang cocok ditemukan',
                        'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri',
                        'infoEmpty' => 'Tidak ada data yang tersedia',
                        'paginate' => [
                            'first' => 'Pertama',
                            'last' => 'Terakhir',
                            'next' => 'Berikutnya',
                            'previous' => 'Sebelumnya',
                        ],
                    ],
                    'responsive' => true,
                    'autoWidth' => false,
                ];

                // Isi data baris
                foreach ($bukus as $i => $buku) {
                    $penulis = $buku->penulis->pluck('nama_penulis')->join(', ');
                    $cover = $buku->url_cover
                        ? '<img src="' .
                            asset('storage/' . $buku->url_cover) .
                            '" style="height:50px;width:auto;border-radius:4px;">'
                        : '<span class="text-muted">No Cover</span>';

                    $btnShow =
                        '<a href="' .
                        route('buku.show', $buku) .
                        '" class="btn btn-xs btn-default text-teal mx-1" title="Lihat"><i class="fa fa-eye"></i></a>';
                    $btnEdit =
                        '<a href="' .
                        route('buku.edit', $buku) .
                        '" class="btn btn-xs btn-default text-primary mx-1" title="Edit"><i class="fa fa-pen"></i></a>';
                    $btnDelete =
                        '<form method="POST" action="' .
                        route('buku.destroy', $buku) .
                        '" style="display:inline-block;" onsubmit="return confirm(\'Yakin hapus ' .
                        e($buku->judul_buku) .
                        '? \');">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>' .
                        '</form>';

                    $checkbox = '<input type="checkbox" name="selected[]" value="' . $buku->id . '">';

                    $config['data'][] = [
                        $checkbox,
                        $i + 1,
                        $cover,
                        $buku->judul_buku,
                        $penulis,
                        $buku->penerbit->nama_penerbit ?? 'N/A',
                        $buku->nomor_panggil,
                        $buku->items->count(),
                        '<nobr>' . $btnShow . $btnEdit . $btnDelete . '</nobr>',
                    ];
                }
            @endphp

            <x-adminlte-datatable id="table_buku" :heads="$heads" :config="$config" striped hoverable bordered
                with-buttons />
        </div>
    </div>
@stop

@section('js')
    @vite(['resources/js/app.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllIcon = document.getElementById('select-all');
            const deleteBtn = document.getElementById('delete-selected-btn');
            const getBoxes = () => Array.from(document.querySelectorAll('#table_buku input[name="selected[]"]'));

            function updateDeleteBtn() {
                deleteBtn.disabled = !getBoxes().some(cb => cb.checked);
            }

            function updateSelectAllIcon() {
                const boxes = getBoxes();
                const all = boxes.length && boxes.every(cb => cb.checked);
                if (all) {
                    selectAllIcon.classList.replace('far', 'fas');
                    selectAllIcon.classList.replace('fa-square', 'fa-check-square');
                } else {
                    selectAllIcon.classList.replace('fas', 'far');
                    selectAllIcon.classList.replace('fa-check-square', 'fa-square');
                }
            }

            // Toggle semua checkbox via icon
            selectAllIcon.addEventListener('click', () => {
                const boxes = getBoxes();
                const shouldUn = boxes.every(cb => cb.checked);
                boxes.forEach(cb => cb.checked = !shouldUn);
                updateSelectAllIcon();
                updateDeleteBtn();
            });

            // Update state tiap checkbox
            getBoxes().forEach(cb => {
                cb.addEventListener('change', () => {
                    updateDeleteBtn();
                    updateSelectAllIcon();
                });
            });

            // Bulk delete
            deleteBtn.addEventListener('click', function() {
                if (!confirm('Yakin hapus buku yang dipilih?')) return;
                const ids = getBoxes().filter(cb => cb.checked).map(cb => cb.value);
                if (!ids.length) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('buku.bulkDestroy') }}";
                form.style.display = 'none';

                const token = document.querySelector('meta[name="csrf-token"]').content;
                form.innerHTML = '<input type="hidden" name="_token" value="' + token + '">' +
                    '<input type="hidden" name="_method" value="DELETE">';
                ids.forEach(id => {
                    form.innerHTML += '<input type="hidden" name="selected[]" value="' + id + '">';
                });

                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>
@stop
