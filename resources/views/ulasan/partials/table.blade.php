@php
    // Map rec badge
    $recBadge = function ($rec) {
        if ($rec === 'recommended') {
            return ['success', '✅ Recommended'];
        }
        if ($rec === 'neutral') {
            return ['secondary', '➖ Netral'];
        }
        if ($rec === 'not') {
            return ['danger', '❌ Not recommended'];
        }
        return ['light', '-'];
    };
@endphp

@if ($data->count() === 0)
    <div class="text-muted">Tidak ada data.</div>
@else
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="thead-light">
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Buku</th>
                    <th>Anggota</th>
                    <th>Label</th>
                    <th>Ulasan</th>
                    <th style="width: 220px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $k)
                    @php
                        $rec = $k->rec();
                        [$badgeClass, $badgeText] = $recBadge($rec);
                        $aud = $k->aud();
                        $isPinned = $k->pinned();
                    @endphp

                    <tr>
                        <td>{{ $k->id }}</td>
                        <td>
                            <div class="font-weight-bold">{{ $k->buku->judul_buku ?? '-' }}</div>
                            <div class="text-muted small">Buku ID: {{ $k->buku_id }}</div>
                        </td>
                        <td>
                            <div class="font-weight-bold">{{ $k->anggota->nama_anggota ?? 'Anonim' }}</div>
                            <div class="text-muted small">Anggota ID: {{ $k->anggota_id }}</div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $badgeClass }}">{{ $badgeText }}</span>
                            @if ($aud)
                                <span class="badge badge-info ml-1">{{ $aud }}</span>
                            @endif
                            @if ($isPinned)
                                <span class="badge badge-warning ml-1">📌 Pinned</span>
                            @endif
                            <div class="text-muted small mt-1">{{ optional($k->created_at)->format('d M Y H:i') }}</div>
                        </td>
                        <td style="max-width: 520px;">
                            <div class="text-wrap">
                                {{ $k->body() }}
                            </div>
                        </td>
                        <td class="text-nowrap">
                            {{-- APPROVE --}}
                            @if ($mode !== 'approved')
                                <form action="{{ route('ulasan.moderate', $k->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success mb-1">
                                        <i class="fas fa-check"></i> ACC
                                    </button>
                                </form>
                            @endif

                            {{-- REJECT --}}
                            @if ($mode !== 'rejected')
                                <form action="{{ route('ulasan.moderate', $k->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm btn-danger mb-1">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            @endif

                            {{-- SET PENDING (balikin) --}}
                            @if ($mode !== 'pending')
                                <form action="{{ route('ulasan.moderate', $k->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="pending">
                                    <button class="btn btn-sm btn-secondary mb-1">
                                        <i class="fas fa-undo"></i> Pending
                                    </button>
                                </form>
                            @endif

                            {{-- PIN / UNPIN --}}
                            <form action="{{ route('ulasan.moderate', $k->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="pinned" value="{{ $isPinned ? 0 : 1 }}">
                                <button class="btn btn-sm btn-warning mb-1">
                                    <i class="fas fa-thumbtack"></i> {{ $isPinned ? 'Unpin' : 'Pin' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
