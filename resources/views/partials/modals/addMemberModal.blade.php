<div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="addMemberModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addMemberModalLabel"><i class="fas fa-user-plus"></i> Daftar Anggota Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('anggota.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_anggota">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_anggota" name="nama_anggota"
                            placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Anggota</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto"
                                accept="image/*">
                            <label class="custom-file-label" for="foto">Pilih file foto...</label>
                        </div>
                        <small class="form-text text-muted">Format image: jpeg, png. Max size: 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Daftar Anggota</button>
                </div>
            </form>
        </div>
    </div>
</div>
