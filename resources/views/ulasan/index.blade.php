@extends('adminlte::page')

@section('title', 'Moderasi Ulasan')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0">Moderasi Ulasan</h1>
        <span class="badge badge-secondary">
            Pending: {{ $pending->count() }} |
            Approved: {{ $approved->count() }} |
            Rejected: {{ $rejected->count() }}
        </span>
    </div>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header p-0">
            <ul class="nav nav-tabs" id="ulasanTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                        Pending <span class="badge badge-warning ml-1">{{ $pending->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="approved-tab" data-toggle="tab" href="#approved" role="tab">
                        Approved <span class="badge badge-success ml-1">{{ $approved->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab">
                        Rejected <span class="badge badge-danger ml-1">{{ $rejected->count() }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="ulasanTabContent">

                {{-- PENDING --}}
                <div class="tab-pane fade show active" id="pending" role="tabpanel">
                    @include('ulasan.partials.table', ['data' => $pending, 'mode' => 'pending'])
                </div>

                {{-- APPROVED --}}
                <div class="tab-pane fade" id="approved" role="tabpanel">
                    @include('ulasan.partials.table', ['data' => $approved, 'mode' => 'approved'])
                </div>

                {{-- REJECTED --}}
                <div class="tab-pane fade" id="rejected" role="tabpanel">
                    @include('ulasan.partials.table', ['data' => $rejected, 'mode' => 'rejected'])
                </div>

            </div>
        </div>
    </div>
@stop
