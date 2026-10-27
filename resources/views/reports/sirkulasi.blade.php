@extends('adminlte::page')

@section('title', 'Laporan Sirkulasi')

@section('content')
    <div class="container-fluid">

        <div class="card report-card animate-fade-up">
            <div class="card-header border-0 bg-white">
                <h3 class="card-title font-weight-bold">
                    🔄 Laporan Sirkulasi
                </h3>
            </div>

            <form method="POST" action="{{ url('/laporan/sirkulasi') }}">
                @csrf

                <div class="card-body">
                    <p class="text-muted mb-4">
                        Filter data sirkulasi berdasarkan status, anggota, dan rentang tanggal.
                    </p>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control select-animate">
                                    <option value="">Semua Status</option>
                                    <option value="DIPINJAM">Dipinjam</option>
                                    <option value="DIKEMBALIKAN">Dikembalikan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Anggota</label>
                                <select name="anggota_id" class="form-control select2" data-placeholder="Pilih anggota">
                                    <option value=""></option>
                                    @foreach ($anggotas as $anggota)
                                        <option value="{{ $anggota->id }}">
                                            {{ $anggota->nama_anggota }} ({{ $anggota->id_anggota }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Dari Tanggal</label>
                                <input type="date" name="from" class="form-control input-animate">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="to" class="form-control input-animate">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer bg-white border-0">
                    <div class="output-actions">
                        <button type="submit" name="output" value="excel" class="btn btn-success btn-animate">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </button>
                        <button type="submit" name="output" value="pdf" class="btn btn-danger btn-animate">
                            <i class="fas fa-file-pdf mr-1"></i> PDF
                        </button>
                        <button type="submit" name="output" value="print" class="btn btn-secondary btn-animate">
                            <i class="fas fa-print mr-1"></i> Print
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Card */
        .report-card {
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
            transition: all .3s ease;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, .08);
        }

        /* Animations */
        .animate-fade-up {
            animation: fadeUp .6s ease forwards;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Inputs */
        .input-animate,
        .select-animate {
            transition: all .2s ease;
        }

        .input-animate:focus,
        .select-animate:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 .15rem rgba(0, 123, 255, .25);
        }

        /* Select2 height fix */
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 4px 10px;
        }

        .select2-selection__rendered {
            line-height: 28px !important;
        }

        .select2-selection__arrow {
            height: 36px !important;
        }

        /* Buttons */
        .output-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .btn-animate {
            transition: transform .25s ease;
        }

        .btn-animate:hover {
            transform: translateY(-2px);
        }

        .btn-animate:active {
            transform: scale(.96);
        }
    </style>
@stop
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            $('.select2').select2({
                placeholder: 'Pilih anggota',
                allowClear: true,
                width: 'resolve'
            });

            $('.btn-animate').on('click', function() {
                $(this).html(
                    '<span class="spinner-border spinner-border-sm mr-2"></span> Memproses...'
                );
            });
        });
    </script>
@stop
