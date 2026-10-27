// 1. Import jQuery dulu, dan expose ke window
import $ from 'jquery';
window.$ = window.jQuery = $;
// 2. Import Bootstrap (yang membutuhkan jQuery/Popper)
import 'bootstrap';
import 'admin-lte';
// 3. Library spesifik halaman
import Stepper from 'bs-stepper';   // untuk form create
import TomSelect from 'tom-select'; // untuk form create
import 'select2';                    // untuk form edit
// Import semua CSS yang dibutuhkan
import 'bootstrap/dist/css/bootstrap.min.css';
import 'admin-lte/dist/css/adminlte.min.css';
import '../css/app.css'; // CSS custom kita
// CSS Spesifik Halaman (di-import di sini agar jadi satu file)
import 'bs-stepper/dist/css/bs-stepper.min.css';        // Untuk form create
import 'tom-select/dist/css/tom-select.bootstrap5.min.css'; // Untuk form create
import 'select2/dist/css/select2.min.css';              // Untuk form edit
import '@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.min.css'; // Tema Select2
import 'datatables.net';
import 'datatables.net-bs4'; 

import Swal from 'sweetalert2';


// Daftarkan Stepper ke window agar bisa dipanggil dari HTML (onclick)
window.Stepper = Stepper;

// Event listener utama, semua kode dijalankan setelah halaman siap
document.addEventListener('DOMContentLoaded', () => {

    // =================================================================
    // =========== LOGIC UNTUK HALAMAN CREATE & EDIT BUKU ============
    // =================================================================
    const formBukuCreate = document.getElementById('form-buku-create');
    if (formBukuCreate) {

        // 1. Inisialisasi Plugin
        const stepperEl = document.getElementById('stepper');
        if (stepperEl) {
            window.stepper = new Stepper(stepperEl, { linear: true, animation: true });
        }
        
        ['kategori_id', 'penulis_id', 'penerbit_id', 'koleksi_id', 'rak_id'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            new TomSelect(el, {
                create: ['kategori_id', 'penulis_id', 'penerbit_id'].includes(id),
                sortField: { field: 'text' },
                hideSelected: true,
            });
        });

        // 2. Element References
        const koleksiSelect = document.getElementById('koleksi_id');

const isDigital = () => {
  const nama = (koleksiSelect?.selectedOptions?.[0]?.dataset?.nama || '').toLowerCase();
  return nama.includes('digital') || nama.includes('ebook') || nama.includes('e-book');
};


        // 3. Digital Collection Logic
        const toggleDigitalFeatures = () => {
            const digital = isDigital();
            document.getElementById('header-step-3')?.classList.toggle('d-none', digital);
            document.getElementById('step-3')?.classList.toggle('d-none', digital);
            document.getElementById('ebook-upload-wrapper')?.classList.toggle('d-none', !digital);
            document.getElementById('call-number-section')?.classList.toggle('d-none', digital);
            document.getElementById('review-call-number-wrapper')?.classList.toggle('d-none', digital);
            document.getElementById('review-eksemplar-section')?.classList.toggle('d-none', digital);
            document.getElementById('review-ebook-wrapper')?.classList.toggle('d-none', !digital);
             const ebookInput = document.getElementById('file_ebook');
  if (ebookInput) {
    ebookInput.required = digital;
    if (!digital) ebookInput.value = '';
  }
        };
        koleksiSelect?.addEventListener('change', toggleDigitalFeatures);



        // 4. Custom Stepper Navigation
        document.getElementById('btn-step2-next').addEventListener('click', () => isDigital() ? stepper.to(4) : stepper.next());
        document.getElementById('btn-step4-back').addEventListener('click', () => isDigital() ? stepper.to(2) : stepper.previous());

        // 5. Live Review Update
        const reviewElements = {
            judul: document.getElementById('review-judul'),
            sub_judul: document.getElementById('review-sub_judul'),
            penulis: document.getElementById('review-penulis'),
            penerbit: document.getElementById('review-penerbit'),
            tahun_terbit: document.getElementById('review-tahun_terbit'),
            tempat_terbit: document.getElementById('review-tempat_terbit'),
            edisi: document.getElementById('review-edisi'),
            kategori: document.getElementById('review-kategori'),
            koleksi: document.getElementById('review-koleksi'),
            deskripsi: document.getElementById('review-deskripsi'),
            nomor_panggil: document.getElementById('review-nomor_panggil'),
            cover: document.getElementById('review-cover'),
            ebook_link: document.getElementById('review-ebook-link'),
        };

        const generateCallNo = () => {
            const katEl = document.getElementById('kategori_id');
            const penEl = document.getElementById('penulis_id');
            const judEl = document.getElementById('judul_buku');
            const kodeKlasifikasi = katEl.selectedOptions[0]?.dataset.kode || 'XXX';
            const namaPenulis = (penEl.selectedOptions[0]?.text.replace(/[^A-Za-z]/g, '') || '');
            const tigaHurufPenulis = namaPenulis.substr(0, 3).toUpperCase() || 'PEN';
            const satuHurufJudul = judEl.value.charAt(0).toLowerCase() || 'j';
            const callNumber = `${kodeKlasifikasi} ${tigaHurufPenulis} ${satuHurufJudul}`;
            document.getElementById('nomor_panggil').value = callNumber;
            if(reviewElements.nomor_panggil) reviewElements.nomor_panggil.textContent = callNumber;
        };
        
        const updateReview = () => {
            for (const key in reviewElements) { if (!reviewElements[key]) return; }
            reviewElements.judul.textContent = document.getElementById('judul_buku').value;
            reviewElements.sub_judul.textContent = document.getElementById('sub_judul').value;
            reviewElements.tahun_terbit.textContent = document.getElementById('tahun_terbit').value;
            reviewElements.tempat_terbit.textContent = document.getElementById('tempat_terbit').value;
            reviewElements.edisi.textContent = document.getElementById('edisi').value;
            reviewElements.deskripsi.textContent = document.querySelector('textarea[name="deskripsi"]').value;
            reviewElements.penulis.textContent = Array.from(document.getElementById('penulis_id').selectedOptions).map(o => o.text).join(', ');
            reviewElements.penerbit.textContent = document.getElementById('penerbit_id').selectedOptions[0]?.text || '';
            reviewElements.kategori.textContent = document.getElementById('kategori_id').selectedOptions[0]?.text || '';
            reviewElements.koleksi.textContent = document.getElementById('koleksi_id').selectedOptions[0]?.text || '';
            generateCallNo();
        };

        const updateEksemplarPreview = (e) => {
            const count = parseInt(e.target.value, 10) || 0;
            const bodyStep3 = document.getElementById('preview-eksemplar-body');
            const bodyStep4 = document.getElementById('review-eksemplar-body');
            if(bodyStep3) bodyStep3.innerHTML = '';
            if(bodyStep4) bodyStep4.innerHTML = '';
            if (count > 0) {
                let rows = '';
                for (let i = 1; i <= count; i++) {
                    rows += `<tr><td>${i}</td><td>${i}</td><td>INV-000${i} (contoh)</td></tr>`;
                }
                if(bodyStep3) bodyStep3.innerHTML = rows;
                if(bodyStep4) bodyStep4.innerHTML = rows;
            }
        };

        // 6. File Previews
        const coverInput = document.getElementById('url_cover');
        if(coverInput) coverInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            document.getElementById('preview-cover-step1').src = url;
            reviewElements.cover.src = url;
            document.getElementById('cover-preview-card').classList.remove('d-none');
        });

        const ebookInput = document.getElementById('file_ebook');
        if(ebookInput) ebookInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            document.getElementById('ebook-filename').textContent = file.name;
            document.getElementById('ebook-ext').textContent = file.name.split('.').pop().toUpperCase();
            reviewElements.ebook_link.textContent = file.name;
            reviewElements.ebook_link.href = url;
            document.getElementById('ebook-preview-card').classList.remove('d-none');
        });

        // 7. Attach Event Listeners
        formBukuCreate.addEventListener('input', updateReview);
        formBukuCreate.addEventListener('change', updateReview);
        const jumlahEksemplarEl = document.getElementById('jumlah_eksemplar');
        if(jumlahEksemplarEl) jumlahEksemplarEl.addEventListener('input', updateEksemplarPreview);

        formBukuCreate.addEventListener('submit', (e) => {
            if (isDigital()) {
                document.querySelectorAll('#step-3 [required]').forEach(el => el.disabled = true);
            } else {
                document.querySelectorAll('#ebook-upload-wrapper [name="file_ebook"]').forEach(el => el.disabled = true);
            }
        });

        // 8. Initial calls
        toggleDigitalFeatures();
        updateReview();
    }

    // =================================================================
    // =========== LOGIC UNTUK HALAMAN EDIT BUKU =====================
    // =================================================================
    const formBukuEdit = document.getElementById('form-buku-edit');
    if (formBukuEdit) {
        // INISIALISASI SELECT2 SECARA MANUAL
        // Kita cari elemen berdasarkan ID dan terapkan plugin Select2
        // Kita juga berikan tema 'bootstrap4' agar tampilannya menyatu dengan AdminLTE
        $('#penulis_id, #kategori_id, #penerbit_id').select2({
            theme: 'bootstrap4',
        });
        
        // Logic untuk preview gambar cover saat file baru dipilih
        const coverInput = document.getElementById('url_cover_edit');
        const coverPreview = document.getElementById('cover-preview');
        coverInput?.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Tampilkan preview gambar
                coverPreview.src = URL.createObjectURL(file);
                // Update teks label dengan nama file baru
                event.target.nextElementSibling.innerText = file.name;
            }
        });

        // Logic untuk tombol unlock/lock input nomor panggil
        const nomorPanggilInput = document.getElementById('nomor_panggil_edit');
        const toggleBtn = document.getElementById('toggle-nomor-panggil');
        toggleBtn?.addEventListener('click', function() {
            const isReadonly = nomorPanggilInput.hasAttribute('readonly');
            nomorPanggilInput.toggleAttribute('readonly', !isReadonly);
            this.querySelector('i').classList.toggle('fa-lock', !isReadonly);
            this.querySelector('i').classList.toggle('fa-lock-open', isReadonly);
            if (isReadonly) nomorPanggilInput.focus();
        });
    }

    // =====================================================
    // ===== LOGIC UNTUK HALAMAN CREATE & EDIT PENULIS =====
    // =====================================================
    const formPenulis = document.getElementById('formPenulis');
    if (formPenulis) {
        const isEdit = !!formPenulis.querySelector('input[name="_method"][value="PUT"]');
        formPenulis.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: isEdit ? 'Perbarui Data Penulis?' : 'Simpan Data Penulis?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: isEdit ? 'Ya, Perbarui!' : 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    }
    // ---- Index Penulis: Hapus dengan SweetAlert ----
    document.querySelectorAll('form.delete-penulis-form')
        .forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin hapus penulis ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        setTimeout(() => {
  $('.alert-success').fadeTo(250, 0).slideUp(250, function() {
    $(this).remove();
  });
}, 6000);

    
    // =================================================================
    // =========== KODE UMUM (Berjalan di semua halaman) =============
    // =================================================================
    $('#deleteModal').on('show.bs.modal', function (e) {
        const button = $(e.relatedTarget);
        const id     = button.data('id');
        const judul  = button.data('judul');
        $('#deleteForm').attr('action', `/buku/${id}`);
        $('#judulBukuToDelete').text(judul);
    });
});