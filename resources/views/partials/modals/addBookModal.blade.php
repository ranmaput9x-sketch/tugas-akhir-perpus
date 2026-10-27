<div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addBookModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="addBookModalLabel"><i class="fas fa-book"></i> Tambah Buku Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="judul_buku">Judul Buku</label>
                            <input type="text" class="form-control" id="judul_buku" name="judul_buku"
                                placeholder="Masukkan judul buku" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="penulis">Penulis</label>
                            <input type="text" class="form-control" id="penulis" name="penulis"
                                placeholder="Masukkan nama penulis" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="penerbit">Penerbit</label>
                            <input type="text" class="form-control" id="penerbit" name="penerbit"
                                placeholder="Masukkan nama penerbit" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="kategori">Kategori</label>
                            <select class="form-control" id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="tahun_terbit">Tahun Terbit</label>
                            <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit"
                                placeholder="YYYY" min="1900" max="{{ date('Y') }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="isbn">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn"
                                placeholder="Masukkan ISBN" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="stok">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" min="1"
                                value="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cover">Cover Buku</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="cover" name="cover"
                                accept="image/*">
                            <label class="custom-file-label" for="cover">Pilih file cover...</label>
                        </div>
                        <small class="form-text text-muted">Format image: jpeg, png. Max size: 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Buku</button>
                </div>
            </form>
        </div>
    </div>
</div>
