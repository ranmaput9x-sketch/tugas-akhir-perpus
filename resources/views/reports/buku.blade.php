@extends('adminlte::page')

@section('title', 'Laporan Buku')

@section('content')
    <div class="container-fluid">

        <div class="card report-card animate-fade-up">
            <div class="card-header border-0 bg-white">
                <h3 class="card-title font-weight-bold">
                    📚 Laporan Buku
                </h3>
            </div>

            <form method="POST" action="{{ route('laporan.buku.generate') }}">
                @csrf

                <div class="card-body">
                    <p class="text-muted mb-4">
                        Ringkasan ketersediaan buku berdasarkan jumlah eksemplar yang tersedia.
                    </p>

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
    <style>
        /* Card Style */
        .report-card {
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.08);
        }

        /* Button Group */
        .output-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        /* Button Animation */
        .btn-animate {
            position: relative;
            overflow: hidden;
            transition: all .25s ease;
        }

        .btn-animate:hover {
            transform: translateY(-2px);
        }

        .btn-animate:active {
            transform: scale(0.96);
        }

        /* Fade Up Animation */
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
        document.querySelectorAll('.btn-animate').forEach(btn => {
            btn.addEventListener('click', function() {
                this.innerHTML =
                    '<span class="spinner-border spinner-border-sm mr-2"></span> Memproses...';
            });
        });
    </script>
@stop
