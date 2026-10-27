@extends('adminlte::page')

@section('title', 'Laporan Anggota')

@section('content')
    <div class="container-fluid">

        <div class="card report-card animate-fade-up">
            <div class="card-header border-0 bg-white">
                <h3 class="card-title font-weight-bold">
                    👥 Laporan Anggota
                </h3>
            </div>

            <form method="POST" action="{{ route('laporan.anggota.generate') }}">
                @csrf

                <div class="card-body">
                    <p class="text-muted mb-4">
                        Menampilkan laporan anggota berdasarkan status keaktifan.
                    </p>

                    <div class="form-group">
                        <label class="font-weight-semibold">Status Anggota</label>
                        <select name="status" class="form-control select-animate">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="non-aktif">Non-Aktif</option>
                            <option value="alumni">Alumni</option>
                            <option value="kadaluwarsa">Kadaluwarsa</option>
                        </select>
                    </div>

                    <div class="output-actions mt-4">
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
    <style>
        .report-card {
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
            transition: all .3s ease;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, .08);
        }

        .output-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        /* Select animation */
        .select-animate {
            transition: all .2s ease;
        }

        .select-animate:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.15rem rgba(40, 167, 69, .25);
        }

        /* Buttons */
        .btn-animate {
            transition: transform .25s ease;
        }

        .btn-animate:hover {
            transform: translateY(-2px);
        }

        .btn-animate:active {
            transform: scale(.96);
        }

        /* Entrance animation */
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
    </style>
@stop
@section('js')
    <script>
        document.querySelectorAll('.btn-animate').forEach(button => {
            button.addEventListener('click', function() {
                this.innerHTML =
                    '<span class="spinner-border spinner-border-sm mr-2"></span> Memproses...';
            });
        });
    </script>
@stop
