@extends('adminlte::page')

@section('title', 'Sirkulasi')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div class="mb-2 mb-md-0">
            <h1 class="m-0">Sirkulasi</h1>
            <small class="text-muted">Peminjaman & pengembalian berbasis barcode (scanner USB mode keyboard)</small>
        </div>

        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="fas fa-sync-alt mr-1"></i>Reset
            </button>
        </div>
    </div>
@stop

@section('css')
    <style>
        /* Keep AdminLTE look, just tighten spacing and make it feel more modern */
        .sirkulasi .card-header .card-title {
            font-weight: 600;
        }

        .sirkulasi .input-group-lg .form-control {
            height: calc(2.875rem + 2px);
        }

        .sirkulasi .nav-tabs .nav-link {
            font-weight: 600;
        }

        .sirkulasi .nav-tabs .nav-link.active {
            border-top: 3px solid rgba(0, 0, 0, .15);
        }

        .sirkulasi .table thead th {
            border-top: 0;
        }

        .sirkulasi .table-hover tbody tr:hover {
            background: rgba(0, 0, 0, .03);
        }

        .sirkulasi .badge-count {
            font-variant-numeric: tabular-nums;
        }

        .sirkulasi .hint {
            line-height: 1.2;
        }
    </style>
@stop

@section('content')
    <div class="sirkulasi">
        <div class="row">
            {{-- KIRI: Anggota + status --}}
            <div class="col-lg-4">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-id-card mr-2"></i>Anggota
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-light">Step 1</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <label class="text-muted small mb-1">Scan kartu anggota</label>
                        <div class="input-group input-group-lg mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            </div>
                            <input type="text" id="scan-anggota" class="form-control"
                                placeholder="Scan atau ketik ID anggota…" autocomplete="off">
                        </div>
                        <small class="text-muted d-block hint mb-3">
                            Fokuskan kursor di input ini, scan, lalu tekan <b>Enter</b>.
                        </small>

                        <div id="info-panel" class="callout callout-info mb-2">
                            <div class="text-muted">
                                Scan ID anggota untuk memulai. Setelah valid, input buku peminjaman akan aktif.
                            </div>
                        </div>

                        <div id="toast-panel"></div>

                    </div>
                </div>

                <div class="card card-outline card-secondary collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-lightbulb mr-2"></i>Panduan cepat
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ol class="pl-3 mb-0 text-muted small">
                            <li>Scan <b>anggota</b> (Step 1).</li>
                            <li>Tab <b>Peminjaman</b>: scan eksemplar satu per satu (Step 2) lalu klik <b>Proses</b>.</li>
                            <li>Tab <b>Pengembalian</b>: scan eksemplar, pilih <b>kondisi</b>, lalu klik <b>Proses</b>.</li>
                            <li>Jika buku tidak ada fisiknya, gunakan tombol <b>Tandai Hilang</b>.</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- KANAN: Transaksi --}}
            <div class="col-lg-8">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exchange-alt mr-2"></i>Transaksi
                        </h3>
                    </div>

                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="sirkulasi-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="pill" href="#tab-pinjam" role="tab"
                                    aria-controls="tab-pinjam" aria-selected="true">
                                    <i class="fas fa-sign-out-alt mr-1"></i>Peminjaman
                                    <span class="badge badge-primary badge-count ml-2">Item: <span
                                            id="badge-pinjam">0</span></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="pill" href="#tab-kembali" role="tab"
                                    aria-controls="tab-kembali" aria-selected="false">
                                    <i class="fas fa-sign-in-alt mr-1"></i>Pengembalian
                                    <span class="badge badge-success badge-count ml-2">Item: <span
                                            id="badge-kembali">0</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            {{-- PEMINJAMAN --}}
                            <div class="tab-pane fade show active" id="tab-pinjam" role="tabpanel">
                                <form id="form-pinjam">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
                                        <div class="mb-2 mb-md-0">
                                            <h5 class="mb-0">
                                                <i class="fas fa-shopping-cart mr-2"></i>Keranjang Pinjam
                                            </h5>
                                            <small class="text-muted">Step 2: scan eksemplar yang akan dipinjam.</small>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-muted small mb-1">Scan buku (eksemplar)</label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-book"></i></span>
                                            </div>
                                            <input type="text" id="scan-buku-pinjam" class="form-control"
                                                placeholder="Scan buku untuk dipinjam…" disabled autocomplete="off">
                                        </div>
                                        <small class="text-muted d-block hint mt-1">
                                            Input ini aktif setelah anggota valid.
                                        </small>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Judul (Kode)</th>
                                                    <th class="text-right" style="width: 90px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pinjam-list">
                                                <tr class="text-muted">
                                                    <td colspan="2">Belum ada buku.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                                        <small class="text-muted mb-2 mb-md-0">
                                            Pastikan daftar sudah benar sebelum diproses.
                                        </small>
                                        <button type="submit" id="btn-proses-pinjam" class="btn btn-primary" disabled>
                                            <i class="fas fa-check mr-1"></i>Proses Peminjaman
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- PENGEMBALIAN --}}
                            <div class="tab-pane fade" id="tab-kembali" role="tabpanel">
                                <form id="form-kembali">
                                    <div class="d-flex align-items-start justify-content-between flex-wrap mb-2">
                                        <div class="mb-2 mb-md-0">
                                            <h5 class="mb-0">
                                                <i class="fas fa-undo mr-2"></i>Keranjang Kembali
                                            </h5>
                                            <small class="text-muted">Scan eksemplar, pilih kondisi, lalu proses.</small>
                                        </div>

                                        <div class="text-right">
                                            <button id="btn-tambah-hilang" type="button"
                                                class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Tandai Hilang
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-muted small mb-1">Scan buku yang dikembalikan</label>
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            </div>
                                            <input type="text" id="scan-buku-kembali" class="form-control"
                                                placeholder="Scan buku yang dikembalikan…" autocomplete="off">
                                        </div>
                                        <small class="text-muted d-block hint mt-1">
                                            Gunakan <b>Tandai Hilang</b> jika buku tidak ada fisiknya.
                                        </small>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Judul (Kode)</th>
                                                    <th style="width: 180px;">Kondisi</th>
                                                    <th class="text-right" style="width: 90px;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="kembali-list">
                                                <tr class="text-muted">
                                                    <td colspan="3">Belum ada buku.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                                        <small class="text-muted mb-2 mb-md-0">
                                            Kondisi <b>Hilang</b> akan diproses sebagai pengembalian hilang.
                                        </small>
                                        <button type="submit" id="btn-proses-kembali" class="btn btn-success" disabled>
                                            <i class="fas fa-check mr-1"></i>Proses Pengembalian
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Tandai Hilang --}}
                <div class="modal fade" id="modal-hilang" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form id="form-hilang" class="modal-content">
                            <div class="modal-header bg-warning">
                                <h5 class="modal-title">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Tandai Buku Hilang
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="text-muted small mb-2">
                                    Pilih buku yang akan ditandai hilang (tanpa scan fisik).
                                </div>
                                <select id="select-hilang" class="form-control">
                                    <option value="">— Pilih Buku —</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-check mr-1"></i>Tandai
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = '{{ csrf_token() }}';

            // --- State ---
            let activeScannerInput = null;
            let currentAnggota = null;
            let lastAnggotaCode = null; // simpan kode scan untuk refresh data setelah perpanjang

            let pinjamCart = new Map();
            let kembaliCart = new Map();
            const kondisiCart = new Map(); // key: item_id, value: baik|rusak|hilang

            // --- Elemen ---
            const infoPanel = document.getElementById('info-panel');
            const toastPanel = document.getElementById('toast-panel');

            const setCalloutType = (el, type) => {
                if (!el) return;
                el.classList.remove('callout-info', 'callout-success', 'callout-danger', 'callout-warning');
                const map = {
                    info: 'callout-info',
                    success: 'callout-success',
                    error: 'callout-danger',
                    warning: 'callout-warning',
                };
                el.classList.add('callout', map[type] || 'callout-info');
            };

            const showProfile = (html) => {
                infoPanel.innerHTML = html;
            };


            // --- Helpers ---
            function esc(v) {
                if (v === null || v === undefined || v === '') return '-';
                return String(v)
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            const showToast = (text, type = 'info') => {
                if (!toastPanel) return;
                toastPanel.innerHTML = `
    <div class="callout ${type === 'success' ? 'callout-success' : type === 'error' ? 'callout-danger' : 'callout-info'} mb-0">
      ${esc(text)}
    </div>
  `;

                // auto hilang
                setTimeout(() => {
                    if (toastPanel) toastPanel.innerHTML = '';
                }, 2500);
            };

            const inputAnggota = document.getElementById('scan-anggota');
            const inputBukuPinjam = document.getElementById('scan-buku-pinjam');
            const pinjamList = document.getElementById('pinjam-list');
            const btnProsesPinjam = document.getElementById('btn-proses-pinjam');
            const formPinjam = document.getElementById('form-pinjam');

            const inputBukuKembali = document.getElementById('scan-buku-kembali');
            const kembaliList = document.getElementById('kembali-list');
            const btnProsesKembali = document.getElementById('btn-proses-kembali');
            const formKembali = document.getElementById('form-kembali');

            // Cegah Enter di input scan memicu submit form
            inputBukuPinjam?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') e.preventDefault();
            });
            inputBukuKembali?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') e.preventDefault();
            });

            // badge init
            const badgePinjamInit = document.getElementById('badge-pinjam');
            if (badgePinjamInit) badgePinjamInit.textContent = '0';
            const badgeKembaliInit = document.getElementById('badge-kembali');
            if (badgeKembaliInit) badgeKembaliInit.textContent = '0';

            const statusBadge = (status) => {
                const map = {
                    'aktif': 'success',
                    'non-aktif': 'secondary',
                    'alumni': 'info',
                    'kadaluwarsa': 'warning',
                };
                const key = (status || '').toLowerCase();
                const cls = map[key] || 'secondary';
                return `<span class="badge badge-${cls} text-uppercase">${esc(status || '-')}</span>`;
            };

            const getMaxActiveItems = () => Number(currentAnggota?.maxActiveItems ?? 2);
            const getRemainingQuota = () => {
                const remaining = Number(currentAnggota?.remainingQuota ?? getMaxActiveItems());
                return Math.max(0, remaining);
            };

            function resetCartsAndUI() {
                pinjamCart.clear();
                kembaliCart.clear();
                kondisiCart.clear();

                const badgePinjam = document.getElementById('badge-pinjam');
                if (badgePinjam) badgePinjam.textContent = '0';
                const badgeKembali = document.getElementById('badge-kembali');
                if (badgeKembali) badgeKembali.textContent = '0';

                if (pinjamList) pinjamList.innerHTML =
                    `<tr class="text-muted"><td colspan="2">Belum ada buku.</td></tr>`;
                if (kembaliList) kembaliList.innerHTML =
                    `<tr class="text-muted"><td colspan="3">Belum ada buku.</td></tr>`;

                if (btnProsesPinjam) btnProsesPinjam.disabled = true;
                if (btnProsesKembali) btnProsesKembali.disabled = true;
            }

            // AdminLTE callout (lebih enak daripada alert hijau besar)


            const ajaxFetch = (url, body) => {
                return fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(body)
                }).then(async (res) => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                });
            };

            // Base URL untuk endpoint perpanjang
            const renewUrlBase = "{{ url('/sirkulasi/peminjaman') }}";

            const refreshAnggota = () => {
                if (!lastAnggotaCode) return Promise.resolve();
                return ajaxFetch('{{ route('sirkulasi.cari-anggota') }}', {
                        kode: lastAnggotaCode
                    })
                    .then((data) => {
                        if (data?.success) {
                            currentAnggota = data.data;
                            showProfile(renderAnggotaCard(currentAnggota));
                        }
                        return data;
                    });
            };

            // Delegasi klik tombol perpanjang di kartu anggota
            infoPanel?.addEventListener('click', (e) => {
                const btn = e.target.closest('.btn-renew');
                if (!btn) return;

                const peminjamanId = btn.getAttribute('data-peminjaman-id');
                if (!peminjamanId) return;

                btn.disabled = true;

                ajaxFetch(`${renewUrlBase}/${peminjamanId}/perpanjang`, {
                        days: 7
                    })
                    .then((data) => {
                        if (!data.success) {
                            showToast(data.message || 'Gagal perpanjang.', 'error');
                            btn.disabled = false;
                            return;
                        }
                        showToast(`Perpanjangan berhasil. Jatuh tempo baru: ${data.new_due_date}`,
                            'success');
                        // refresh supaya badge/tombol ikut update
                        return refreshAnggota();
                    })
                    .catch((err) => {
                        showToast(err.message || 'Gagal perpanjang.', 'error');
                        btn.disabled = false;
                    });
            });

            const renderAnggotaCard = (a) => {
                // kadang backend kirim "id", bukan "id_anggota"
                const idAnggota = a.id_anggota || a.id || '-';

                return `
      <div class="d-flex align-items-start">
        <img src="${esc(a.foto)}" class="img-thumbnail mr-3"
             style="width:88px;height:88px;object-fit:cover;">
        <div class="flex-grow-1">
          <div class="d-flex justify-content-between align-items-start">
            <div>
              <div class="h6 mb-0 font-weight-bold">${esc(a.nama)}</div>
              <div class="text-muted small">
                ID: <b>${esc(idAnggota)}</b> • Nomor Induk: <b>${esc(a.nomor_induk)}</b>
              </div>
            </div>
            <div>${statusBadge(a.status)}</div>
          </div>

          <div class="row mt-2 small">
            <div class="col-6 mb-1"><span class="text-muted">Tingkat:</span> <b>${esc(a.tingkat)}</b></div>
            <div class="col-6 mb-1"><span class="text-muted">JK:</span> <b>${esc(a.jenis_kelamin)}</b></div>
            <div class="col-6 mb-1"><span class="text-muted">Telp:</span> <b>${esc(a.no_telepon)}</b></div>
            <div class="col-12 mb-1"><span class="text-muted">Email:</span> <b>${esc(a.email)}</b></div>
            <div class="col-12 mb-1"><span class="text-muted">Alamat:</span> <b>${esc(a.alamat)}</b></div>
          </div>

          <hr class="my-2">
          <div class="mt-1 small">
            <span class="text-muted">Pinjaman aktif:</span> <b>${esc(a.pinjamanAktif)}</b>
          </div>

          ${(() => {
            const loans = (a.activeLoans || []);
            if (!loans.length) return '';

            const rows = loans.map((p) => {
              const can = !!p.can_renew;
              const badge = can
                ? `<span class="badge badge-success">Bisa diperpanjang</span>`
                : `<span class="badge badge-secondary">Tidak bisa diperpanjang</span>`;
              const btn = can
                ? `<button type="button" class="btn btn-sm btn-warning btn-renew" data-peminjaman-id="${esc(p.id)}">Perpanjang +7 hari</button>`
                : '';

              return `
                                                        <div class="border rounded p-2 mb-2">
                                                          <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                              <div class="small"><b>Transaksi #${esc(p.id)}</b></div>
                                                              <div class="small text-muted">Pinjam: <b>${esc(p.tanggal_pinjam)}</b></div>
                                                              <div class="small text-muted">Jatuh tempo: <b>${esc(p.tanggal_kembali)}</b></div>
                                                              <div class="small text-muted">Batas max (aturan sekolah): <b>${esc(p.max_due_date)}</b></div>
                                                            </div>
                                                            <div class="text-right">
                                                              <div class="mb-2">${badge}</div>
                                                              ${btn}
                                                            </div>
                                                          </div>
                                                        </div>
                                                      `;
            }).join('');

            return [
  '<hr class="my-2">',
  '<div class="small font-weight-bold mb-2">Perpanjang Peminjaman (maks 14 hari)</div>',
  rows
].join('');


          })()}
        </div>
      </div>
    `;
            };

            // --- Fokus scanner ---
            inputAnggota?.addEventListener('focus', () => activeScannerInput = inputAnggota);
            inputBukuPinjam?.addEventListener('focus', () => activeScannerInput = inputBukuPinjam);
            inputBukuKembali?.addEventListener('focus', () => activeScannerInput = inputBukuKembali);

            // --- Logika Peminjaman (Scan Anggota) ---
            inputAnggota?.addEventListener('keyup', (e) => {
                if (e.key !== 'Enter' || !e.target.value) return;

                lastAnggotaCode = e.target.value.trim();

                e.target.disabled = true;

                ajaxFetch('{{ route('sirkulasi.cari-anggota') }}', {
                        kode: lastAnggotaCode
                    })
                    .then((data) => {
                        if (!data.success) {
                            showToast(data.message || 'Anggota tidak ditemukan.', 'error');
                            e.target.disabled = false;
                            return;
                        }

                        currentAnggota = data.data;

                        // reset tampilan + cart untuk anggota yang baru
                        resetCartsAndUI();

                        // isi dropdown "hilang"
                        const select = document.getElementById('select-hilang');
                        if (select) {
                            select.innerHTML = '<option value="">— Pilih Buku —</option>';
                            (data.data.borrowed || []).forEach((item) => {
                                const opt = document.createElement('option');
                                opt.value = item.kode;
                                opt.textContent = `${item.judul} (${item.kode})`;
                                select.appendChild(opt);
                            });
                        }

                        // tampilkan kartu anggota
                        showProfile(renderAnggotaCard(currentAnggota));
                        if (toastPanel) toastPanel.innerHTML = ''; // optional bersihin notif


                        // aktifkan input pinjam hanya jika masih ada kuota
                        inputBukuPinjam.disabled = getRemainingQuota() === 0;
                        inputBukuPinjam.focus();
                        activeScannerInput = inputBukuPinjam;
                    })
                    .catch((err) => {
                        showToast(err.message || 'Terjadi error', 'error');
                        e.target.disabled = false;
                    });
            });

            // --- Scan Buku Pinjam ---
            inputBukuPinjam?.addEventListener('keyup', (e) => {
                if (e.key !== 'Enter' || !e.target.value) return;

                const kodeBuku = e.target.value.trim();
                e.target.value = '';

                if (!currentAnggota) {
                    showToast('Scan anggota dulu.', 'error');
                    inputAnggota.focus();
                    return;
                }

                // Batasi sesuai kuota (maks 2 aktif). Kuota sudah mempertimbangkan pinjaman yang masih berjalan.
                const remainingQuota = getRemainingQuota();
                if (remainingQuota <= 0) {
                    showToast(`Limit pinjam aktif ${getMaxActiveItems()} eksemplar sudah penuh.`, 'error');
                    return;
                }
                if (pinjamCart.size >= remainingQuota) {
                    showToast(`Sisa kuota pinjam: ${remainingQuota}. Tidak bisa menambah lagi.`, 'error');
                    return;
                }

                if (pinjamCart.has(kodeBuku)) {
                    showToast(`Buku ${kodeBuku} sudah ada di daftar.`, 'error');
                    return;
                }

                ajaxFetch('{{ route('sirkulasi.cari-buku') }}', {
                        kode: kodeBuku,
                        mode: 'pinjam'
                    })
                    .then((data) => {
                        if (!data.success) {
                            showToast(data.message || 'Buku tidak valid.', 'error');
                            return;
                        }
                        pinjamCart.set(data.data.kode, data.data);
                        updatePinjamList();
                        showToast(`Buku "${data.data.judul}" ditambahkan.`, 'success');
                    })
                    .catch((err) => {
                        console.error(err);
                        showToast(err?.message || 'Terjadi error.', 'error');
                    });
            });

            const updatePinjamList = () => {
                pinjamList.innerHTML = '';
                const badgePinjam = document.getElementById('badge-pinjam');
                if (badgePinjam) badgePinjam.textContent = String(pinjamCart.size);

                const remainingQuota = getRemainingQuota();
                const overLimit = pinjamCart.size > remainingQuota;
                btnProsesPinjam.disabled = pinjamCart.size === 0 || !currentAnggota || remainingQuota === 0 ||
                    overLimit;

                // Disable input kalau kuota sudah habis atau cart sudah memenuhi kuota
                if (currentAnggota) {
                    inputBukuPinjam.disabled = remainingQuota === 0 || pinjamCart.size >= remainingQuota;
                }

                if (pinjamCart.size === 0) {
                    pinjamList.innerHTML = `<tr class="text-muted"><td colspan="2">Belum ada buku.</td></tr>`;
                    return;
                }

                pinjamCart.forEach((item) => {
                    pinjamList.innerHTML += `
        <tr>
          <td>${esc(item.judul)} (${esc(item.kode)})</td>
          <td class="text-right">
            <button type="button" class="btn btn-sm btn-danger px-3 py-2"
                    onclick="removeFromPinjamCart('${esc(item.kode)}')">&times;</button>
          </td>
        </tr>`;
                });
            };

            window.removeFromPinjamCart = (kode) => {
                pinjamCart.delete(kode);
                updatePinjamList();
            };

            // Jangan biarkan form submit via enter
            formPinjam?.addEventListener('submit', (e) => e.preventDefault());

            // Proses pinjam hanya lewat tombol
            btnProsesPinjam?.addEventListener('click', (e) => {
                e.preventDefault();
                btnProsesPinjam.disabled = true;

                const itemIds = Array.from(pinjamCart.values()).map((item) => item.id);

                ajaxFetch('{{ route('sirkulasi.proses-pinjam') }}', {
                        anggota_id: currentAnggota.db_id,
                        item_ids: itemIds
                    })
                    .then((data) => {
                        showToast(data.message || 'Selesai.', data.success ? 'success' : 'error');
                        if (data.success) setTimeout(() => location.reload(), 1200);
                        else btnProsesPinjam.disabled = false;
                    })
                    .catch((err) => {
                        console.error(err);
                        showToast(err?.message || 'Terjadi error.', 'error');
                        btnProsesPinjam.disabled = false;
                    });
            });

            // --- Logika Pengembalian (Scan Buku Kembali) ---
            inputBukuKembali?.addEventListener('keyup', (e) => {
                if (e.key !== 'Enter' || !e.target.value) return;

                const kode = e.target.value.trim();
                e.target.value = '';

                if (kembaliCart.has(kode)) {
                    showToast(`Buku ${kode} sudah ada di daftar.`, 'error');
                    return;
                }

                ajaxFetch('{{ route('sirkulasi.cari-buku') }}', {
                        kode,
                        mode: 'kembali'
                    })
                    .then((data) => {
                        if (!data.success) {
                            showToast(data.message || 'Buku tidak valid.', 'error');
                            return;
                        }

                        kembaliCart.set(data.data.kode, data.data);
                        kondisiCart.set(data.data.id, 'baik');
                        updateKembaliList();

                        showToast(
                            `Buku "${data.data.judul}" siap dikembalikan. Peminjam: ${data.data.peminjam || '-'}`,
                            'success');

                    })
                    .catch((err) => {
                        console.error(err);
                        showToast(err?.message || 'Terjadi error.', 'error');
                    });
            });

            // Buka modal hilang
            const btnTambahHilang = document.getElementById('btn-tambah-hilang');
            if (btnTambahHilang) {
                btnTambahHilang.onclick = () => $('#modal-hilang').modal('show');
            }

            // Submit modal hilang
            const formHilang = document.getElementById('form-hilang');
            if (formHilang) {
                formHilang.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const kode = document.getElementById('select-hilang')?.value;
                    if (!kode) return showToast('Pilih buku dulu.', 'error');

                    ajaxFetch('{{ route('sirkulasi.cari-buku') }}', {
                            kode,
                            mode: 'kembali'
                        })
                        .then((data) => {
                            if (!data.success) throw new Error(data.message || 'Gagal');
                            kembaliCart.set(data.data.kode, data.data);
                            kondisiCart.set(data.data.id, 'hilang');
                            updateKembaliList();
                            $('#modal-hilang').modal('hide');
                            showToast(`Buku "${data.data.judul}" ditandai hilang.`, 'success');

                        })
                        .catch((err) => showToast(err.message, 'error'));
                });
            }

            // Proses kembali
            // Jangan biarkan form submit via enter
            formKembali?.addEventListener('submit', (e) => e.preventDefault());

            // Proses kembali hanya lewat tombol
            btnProsesKembali?.addEventListener('click', (e) => {
                e.preventDefault();
                btnProsesKembali.disabled = true;

                const itemIds = Array.from(kembaliCart.values()).map((i) => i.id);
                const kondisi = {};
                itemIds.forEach((id) => kondisi[id] = kondisiCart.get(id) || 'baik');

                ajaxFetch('{{ route('sirkulasi.proses-kembali') }}', {
                        item_ids: itemIds,
                        kondisi
                    })
                    .then((data) => {
                        showToast(data.message || 'Selesai.', data.success ? 'success' : 'error');
                        if (data.success) setTimeout(() => location.reload(), 1200);
                        else btnProsesKembali.disabled = false;
                    })
                    .catch((err) => {
                        console.error(err);
                        showToast(err?.message || 'Terjadi error.', 'error');
                        btnProsesKembali.disabled = false;
                    });
            });

            // Update tabel pengembalian
            function updateKembaliList() {
                kembaliList.innerHTML = '';
                const badgeKembali = document.getElementById('badge-kembali');
                if (badgeKembali) badgeKembali.textContent = String(kembaliCart.size);

                btnProsesKembali.disabled = kembaliCart.size === 0;

                if (kembaliCart.size === 0) {
                    kembaliList.innerHTML = `<tr class="text-muted"><td colspan="3">Belum ada buku.</td></tr>`;
                    return;
                }

                kembaliCart.forEach((item) => {
                    const sel = kondisiCart.get(item.id) || 'baik';

                    kembaliList.innerHTML += `
        <tr data-id="${item.id}">
          <td>${esc(item.judul)} (${esc(item.kode)})</td>
          <td>
            <select class="form-control form-control-sm kondisi-select">
              <option value="baik"${sel === 'baik' ? ' selected' : ''}>Baik</option>
              <option value="rusak"${sel === 'rusak' ? ' selected' : ''}>Rusak</option>
              <option value="hilang"${sel === 'hilang' ? ' selected' : ''}>Hilang</option>
            </select>
          </td>
          <td class="text-right">
            <button type="button" class="btn btn-sm btn-danger px-3 py-2"
                    onclick="removeFromKembaliCart('${esc(item.kode)}')">&times;</button>
          </td>
        </tr>`;
                });

                // attach change handler
                document.querySelectorAll('.kondisi-select').forEach((select) => {
                    select.onchange = (e) => {
                        const tr = e.target.closest('tr');
                        const id = +tr.dataset.id;
                        kondisiCart.set(id, e.target.value);
                    };
                });
            }

            window.removeFromKembaliCart = (kode) => {
                const item = kembaliCart.get(kode);
                if (item) kondisiCart.delete(item.id); // bersihin kondisi biar gak nyisa
                kembaliCart.delete(kode);
                updateKembaliList();
            };
        });
    </script>
@stop
