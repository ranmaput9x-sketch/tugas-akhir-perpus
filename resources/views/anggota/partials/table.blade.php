@forelse($anggotas as $item)
    <tr>
        <td>{{ $item->id_anggota }}</td>
        <td>{{ $item->nama_anggota }}</td>
        <td>{{ $item->tanggal_lahir }}</td>
        <td>{{ $item->jenis_kelamin }}</td>
        <td>{{ $item->alamat }}</td>
        <td>
            @if ($item->foto)
                <img src="{{ asset('storage/foto/' . $item->foto) }}" width="60" class="img-thumbnail">
            @else
                <span class="text-muted">Tidak ada</span>
            @endif
        </td>
        <td>
            <a href="{{ route('anggota.edit', $item->id) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('anggota.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger btn-sm btn-delete">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center">Data tidak ditemukan.</td>
    </tr>
@endforelse
