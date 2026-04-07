<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Minat Rothwell-Miller (RMIB)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/karir-form.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- SortableJS for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <!-- Custom CSS for Drag & Drop Form -->
    <link href="{{ asset('css/karir-form-dragdrop.css') }}" rel="stylesheet">
    <style>
        /* Background - requires asset() helper */
        body {
            background: url('{{ asset('assets/bg.svg') }}') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }
    </style>
</head>

<body>
    <div class="container py-4">
        <div class="main-card">
            <div class="card-header bg-primary text-white p-4">
                <h1 class="h3 mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Tes Minat Rothwell-Miller (RMIB)
                </h1>
                <p class="mb-0 mt-2">Peserta: {{ $dataDiri->nama }} ({{ $gender == 'L' ? 'Laki-laki' : 'Perempuan' }})
                </p>
            </div>
            <div class="card-body p-4">
                <!-- Alert Petunjuk -->
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Petunjuk Pengerjaan</h5>
                    <hr>
                    <p class="mb-2">
                        <strong>Seret pekerjaan ke peringkat 1 sampai 12 pada masing-masing kelompok.
                            Peringkat 1 untuk pekerjaan yang paling Anda sukai, dan peringkat 12 untuk yang paling tidak
                            disukai.</strong>
                    </p>
                    <hr>
                    <p class="mb-0">
                        <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                        <strong>Penting: Tes ini mengukur tingkat kesukaan Anda terhadap suatu pekerjaan,
                            terlepas dari pertimbangan gaji, beban kerja, atau faktor eksternal lainnya.</strong>
                    </p>
                </div>
                <!-- Drag Instruction -->
                <div class="drag-instruction">
                    <i class="fas fa-hand-pointer"></i>
                    <div class="instruction-text">
                        <strong><i class="fas fa-arrows-alt-v me-1"></i>Seret & Urutkan Pekerjaan!</strong>
                        <strong>Drag pekerjaan ke atas untuk peringkat lebih tinggi (1 = paling disukai), ke bawah untuk
                            peringkat lebih rendah (12 = paling tidak disukai).</strong>
                    </div>
                </div>

                <!-- One-Shot Warning -->
                <div class="one-shot-warning">
                    <i class="fas fa-lock"></i>
                    <div class="warning-text">
                        <strong><i class="fas fa-exclamation-circle me-1"></i>PERHATIAN: Jawaban Tidak Dapat
                            Diubah!</strong>
                        <strong> Setelah Anda melanjutkan ke kelompok berikutnya, urutan akan terkunci dan
                            tidak bisa diubah.</strong>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-bar-custom">
                        <div class="progress-bar-fill" id="progressBar" style="width: 10%"></div>
                    </div>
                    <div class="progress-text">
                        <span id="progressText">Kelompok A (1/10)</span>
                    </div>
                </div>

                <!-- Group Pagination -->
                <div class="group-pagination" id="groupPagination">
                    @foreach ($pekerjaanPerKelompok as $index => $daftarPekerjaan)
                        @php $kelompokHuruf = chr(65 + $index); @endphp
                        <button type="button" class="page-btn {{ $index === 0 ? 'active' : '' }}"
                            data-group="{{ $index }}" title="Kelompok {{ $kelompokHuruf }}">
                            {{ $kelompokHuruf }}
                        </button>
                    @endforeach
                    <button type="button" class="page-btn" data-group="final" title="Langkah Akhir">
                        <i class="fas fa-star" style="font-size: 14px;"></i>
                    </button>
                </div>

                <form id="rmibForm" method="POST"
                    action="{{ route('karir.tes.store', ['data_diri' => $dataDiri->id]) }}">
                    @csrf

                    <!-- Group Pages with Drag & Drop -->
                    @foreach ($pekerjaanPerKelompok as $index => $daftarPekerjaan)
                        @php $kelompokHuruf = chr(65 + $index); @endphp
                        <div class="group-page {{ $index === 0 ? 'active' : '' }}" data-group="{{ $index }}">
                            <div class="group-header">
                                <h2><i class="fas fa-folder-open me-2"></i>Kelompok {{ $kelompokHuruf }}</h2>
                                <p>Seret pekerjaan untuk mengurutkan dari yang paling disukai (atas) ke yang paling
                                    tidak disukai (bawah)</p>
                            </div>

                            <div class="ranking-container">
                                <!-- Rank Labels (Desktop) -->
                                <div class="ranking-instructions d-none d-md-block">
                                    <div class="rank-labels">
                                        <div class="rank-label top-rank">🥇 1</div>
                                        <div class="rank-label top-rank">🥈 2</div>
                                        <div class="rank-label top-rank">🥉 3</div>
                                        <div class="rank-label mid-rank">4</div>
                                        <div class="rank-label mid-rank">5</div>
                                        <div class="rank-label mid-rank">6</div>
                                        <div class="rank-label mid-rank">7</div>
                                        <div class="rank-label mid-rank">8</div>
                                        <div class="rank-label mid-rank">9</div>
                                        <div class="rank-label low-rank">10</div>
                                        <div class="rank-label low-rank">11</div>
                                        <div class="rank-label low-rank">12</div>
                                    </div>
                                </div>

                                <!-- Sortable List -->
                                <div class="sortable-list" id="sortable_{{ $kelompokHuruf }}"
                                    data-kelompok="{{ $kelompokHuruf }}">
                                    @foreach ($daftarPekerjaan as $pekerjaan)
                                        <div class="sortable-item" data-pekerjaan="{{ $pekerjaan }}">
                                            <i class="fas fa-grip-vertical drag-handle"></i>
                                            <div class="item-rank rank-mid">{{ $loop->iteration }}</div>
                                            <span class="item-name">{{ $pekerjaan }}</span>
                                            <span class="item-medal"></span>
                                            <!-- Hidden input for form submission -->
                                            <input type="hidden"
                                                name="jawaban[{{ $kelompokHuruf }}][{{ $pekerjaan }}]"
                                                value="{{ $loop->iteration }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Final Page: Top Choices -->
                    <div class="group-page" data-group="final">
                        <div class="group-header"
                            style="background: linear-gradient(135deg, #5055ff 0%, #3a3fd1 100%);">
                            <h2><i class="fas fa-star me-2"></i>Langkah Terakhir</h2>
                            <p>Pilih 3 pekerjaan favorit Anda dari semua kelompok</p>
                        </div>

                        <div class="top-choice-section-final">
                            <h4 class="mb-3"><i class="fas fa-trophy me-2"></i>Pekerjaan yang Paling Anda Sukai (Top
                                3)</h4>
                            <p class="text-muted small">Pilih dari daftar atau ketik pekerjaan bebas (apa saja jika
                                tidak ada di daftar) yang paling mewakili
                                minat Anda.</p>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="top1" class="form-label fw-bold">
                                        <span class="badge bg-warning text-dark me-2">1</span>Pilihan Peringkat 1
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="top1" id="top1"
                                            class="form-control top-choice-input" placeholder="Pilih atau ketik..."
                                            required autocomplete="off">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu dropdown-menu-end job-dropdown"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($pekerjaanPerKelompok as $idx => $daftarPekerjaan)
                                                @php $kH = chr(65 + $idx); @endphp
                                                <li>
                                                    <h6 class="dropdown-header">Kelompok {{ $kH }}</h6>
                                                </li>
                                                @foreach ($daftarPekerjaan as $job)
                                                    <li><a class="dropdown-item job-option" href="#"
                                                            data-target="top1">{{ $job }}</a></li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="top2" class="form-label fw-bold">
                                        <span class="badge bg-secondary me-2">2</span>Pilihan Peringkat 2
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="top2" id="top2"
                                            class="form-control top-choice-input" placeholder="Pilih atau ketik..."
                                            required autocomplete="off">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu dropdown-menu-end job-dropdown"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($pekerjaanPerKelompok as $idx => $daftarPekerjaan)
                                                @php $kH = chr(65 + $idx); @endphp
                                                <li>
                                                    <h6 class="dropdown-header">Kelompok {{ $kH }}</h6>
                                                </li>
                                                @foreach ($daftarPekerjaan as $job)
                                                    <li><a class="dropdown-item job-option" href="#"
                                                            data-target="top2">{{ $job }}</a></li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="top3" class="form-label fw-bold">
                                        <span class="badge bg-info me-2">3</span>Pilihan Peringkat 3
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="top3" id="top3"
                                            class="form-control top-choice-input" placeholder="Pilih atau ketik..."
                                            required autocomplete="off">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu dropdown-menu-end job-dropdown"
                                            style="max-height: 300px; overflow-y: auto;">
                                            @foreach ($pekerjaanPerKelompok as $idx => $daftarPekerjaan)
                                                @php $kH = chr(65 + $idx); @endphp
                                                <li>
                                                    <h6 class="dropdown-header">Kelompok {{ $kH }}</h6>
                                                </li>
                                                @foreach ($daftarPekerjaan as $job)
                                                    <li><a class="dropdown-item job-option" href="#"
                                                            data-target="top3">{{ $job }}</a></li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="top-choice-section-final mt-4">
                            <h5 class="mb-2"><i class="fas fa-plus-circle me-2"></i>(Opsional) Pekerjaan Lain yang
                                Anda Minati</h5>
                            <p class="text-muted small mb-3">Jika ada pekerjaan lain yang Anda minati namun tidak ada
                                di
                                daftar.</p>
                            <input type="text" name="pekerjaan_lain" id="pekerjaan_lain" class="form-control"
                                placeholder="Contoh: Data Scientist, UX Designer, dll.">
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="nav-buttons">
                        <button type="button" class="btn btn-next" id="btnNext">
                            Lanjutkan ke Kelompok Berikutnya<i class="fas fa-arrow-right ms-2"></i>
                        </button>
                        <button type="submit" class="btn btn-success" id="btnSubmit" style="display: none;">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalGroups = {{ count($pekerjaanPerKelompok) }};
            const dataDiriId = {{ $dataDiri->id }};
            const STORAGE_KEY = `rmib_progress_${dataDiriId}`;
            const isRetake = {{ isset($isRetake) && $isRetake ? 'true' : 'false' }};

            // Jika ini adalah tes ulang, hapus progress lama agar mulai dari awal
            if (isRetake) {
                localStorage.removeItem(STORAGE_KEY);
                console.log('Retake detected: cleared old progress');
            }

            let currentGroup = 0;
            const completedGroups = new Set();
            const sortableInstances = {};

            const groupPages = document.querySelectorAll('.group-page');
            const pageButtons = document.querySelectorAll('.group-pagination .page-btn');
            const btnNext = document.getElementById('btnNext');
            const btnSubmit = document.getElementById('btnSubmit');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');

            // ============================================
            // AUTO-SAVE & RESTORE FUNCTIONS
            // ============================================

            // Save current progress to localStorage
            function saveProgress() {
                try {
                    const progress = {
                        currentGroup: currentGroup,
                        completedGroups: Array.from(completedGroups),
                        rankings: {},
                        topChoices: {
                            top1: document.getElementById('top1')?.value || '',
                            top2: document.getElementById('top2')?.value || '',
                            top3: document.getElementById('top3')?.value || ''
                        },
                        pekerjaanLain: document.getElementById('pekerjaan_lain')?.value || '',
                        timestamp: new Date().toISOString()
                    };

                    // Save rankings for each group
                    document.querySelectorAll('.sortable-list').forEach(list => {
                        const kelompok = list.dataset.kelompok;
                        const items = list.querySelectorAll('.sortable-item');
                        progress.rankings[kelompok] = [];
                        items.forEach(item => {
                            progress.rankings[kelompok].push(item.dataset.pekerjaan);
                        });
                    });

                    localStorage.setItem(STORAGE_KEY, JSON.stringify(progress));
                    console.log('Progress saved:', new Date().toLocaleTimeString());
                } catch (e) {
                    console.error('Failed to save progress:', e);
                }
            }

            // Restore progress from localStorage
            function restoreProgress() {
                try {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    if (!saved) return false;

                    const progress = JSON.parse(saved);

                    // Check if data is not too old (24 hours max)
                    const savedTime = new Date(progress.timestamp);
                    const now = new Date();
                    const hoursDiff = (now - savedTime) / (1000 * 60 * 60);
                    if (hoursDiff > 24) {
                        localStorage.removeItem(STORAGE_KEY);
                        return false;
                    }

                    // Restore rankings for each group
                    if (progress.rankings) {
                        Object.keys(progress.rankings).forEach(kelompok => {
                            const list = document.getElementById(`sortable_${kelompok}`);
                            if (!list) return;

                            const orderedItems = progress.rankings[kelompok];
                            orderedItems.forEach(pekerjaan => {
                                const item = list.querySelector(`[data-pekerjaan="${pekerjaan}"]`);
                                if (item) {
                                    list.appendChild(item);
                                }
                            });
                            updateRanks(kelompok);
                        });
                    }

                    // Restore completed groups
                    if (progress.completedGroups && progress.completedGroups.length > 0) {
                        progress.completedGroups.forEach(groupIdx => {
                            completedGroups.add(groupIdx);

                            // Lock the group visually
                            pageButtons[groupIdx].classList.add('completed', 'locked');
                            pageButtons[groupIdx].classList.remove('active');

                            const kelompok = String.fromCharCode(65 + groupIdx);
                            if (sortableInstances[kelompok]) {
                                sortableInstances[kelompok].option('disabled', true);
                            }

                            const groupPage = document.querySelector(
                                `.group-page[data-group="${groupIdx}"]`);
                            if (groupPage) {
                                groupPage.classList.add('locked');
                            }
                        });
                    }

                    // Restore current group
                    if (typeof progress.currentGroup === 'number') {
                        currentGroup = progress.currentGroup;
                    }

                    // Restore top choices
                    if (progress.topChoices) {
                        const top1 = document.getElementById('top1');
                        const top2 = document.getElementById('top2');
                        const top3 = document.getElementById('top3');
                        if (top1 && progress.topChoices.top1) top1.value = progress.topChoices.top1;
                        if (top2 && progress.topChoices.top2) top2.value = progress.topChoices.top2;
                        if (top3 && progress.topChoices.top3) top3.value = progress.topChoices.top3;
                    }

                    // Restore pekerjaan lain
                    if (progress.pekerjaanLain) {
                        const pekerjaanLain = document.getElementById('pekerjaan_lain');
                        if (pekerjaanLain) pekerjaanLain.value = progress.pekerjaanLain;
                    }

                    return true;
                } catch (e) {
                    console.error('Failed to restore progress:', e);
                    return false;
                }
            }

            // Clear saved progress
            function clearProgress() {
                localStorage.removeItem(STORAGE_KEY);
                console.log('Progress cleared');
            }

            // Initialize all sortable lists
            document.querySelectorAll('.sortable-list').forEach(list => {
                const kelompok = list.dataset.kelompok;
                sortableInstances[kelompok] = new Sortable(list, {
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    handle: '.sortable-item',
                    onEnd: function(evt) {
                        updateRanks(kelompok);
                        saveProgress(); // Auto-save on drag end
                    }
                });
            });

            // Update ranks after drag
            function updateRanks(kelompok) {
                const list = document.getElementById(`sortable_${kelompok}`);
                const items = list.querySelectorAll('.sortable-item');

                items.forEach((item, index) => {
                    const rank = index + 1;
                    const pekerjaan = item.dataset.pekerjaan;

                    // Update hidden input
                    const input = item.querySelector('input[type="hidden"]');
                    input.value = rank;

                    // Update visual rank badge
                    const rankBadge = item.querySelector('.item-rank');
                    rankBadge.textContent = rank;
                    rankBadge.className = 'item-rank';

                    if (rank === 1) rankBadge.classList.add('rank-1');
                    else if (rank === 2) rankBadge.classList.add('rank-2');
                    else if (rank === 3) rankBadge.classList.add('rank-3');
                    else if (rank <= 9) rankBadge.classList.add('rank-mid');
                    else rankBadge.classList.add('rank-low');

                    // Update medal
                    const medal = item.querySelector('.item-medal');
                    if (rank === 1) medal.textContent = '🥇';
                    else if (rank === 2) medal.textContent = '🥈';
                    else if (rank === 3) medal.textContent = '🥉';
                    else medal.textContent = '';
                });
            }

            // Initialize ranks for all groups
            document.querySelectorAll('.sortable-list').forEach(list => {
                updateRanks(list.dataset.kelompok);
            });

            // Page buttons
            pageButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetGroup = this.dataset.group;

                    if (targetGroup !== 'final' && completedGroups.has(parseInt(targetGroup))) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kelompok Terkunci',
                            text: 'Kelompok ini sudah selesai dan tidak dapat diubah.',
                            confirmButtonColor: '#4361ee'
                        });
                        return;
                    }

                    if (targetGroup === 'final' && currentGroup !== totalGroups) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Belum Waktunya',
                            text: 'Selesaikan semua kelompok terlebih dahulu.',
                            confirmButtonColor: '#4361ee'
                        });
                    }
                });
            });

            // Next button
            btnNext.addEventListener('click', () => {
                Swal.fire({
                    title: 'Lanjutkan ke Kelompok Berikutnya?',
                    html: `<p>Urutan pekerjaan pada <strong>Kelompok ${String.fromCharCode(65 + currentGroup)}</strong> akan <span class="text-danger fw-bold">TERKUNCI</span>.</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4361ee',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Cek Ulang'
                }).then((result) => {
                    if (result.isConfirmed) {
                        lockCurrentGroup();
                        if (currentGroup < totalGroups) {
                            goToGroup(currentGroup + 1);
                        }
                    }
                });
            });

            // Form submit
            document.getElementById('rmibForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const top1 = document.getElementById('top1').value.trim();
                const top2 = document.getElementById('top2').value.trim();
                const top3 = document.getElementById('top3').value.trim();

                if (!top1 || !top2 || !top3) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Belum Lengkap!',
                        text: 'Mohon pilih 3 pekerjaan favorit Anda.',
                        confirmButtonColor: '#4361ee'
                    });
                    return;
                }

                const top1Lower = top1.toLowerCase();
                const top2Lower = top2.toLowerCase();
                const top3Lower = top3.toLowerCase();
                if (top1Lower === top2Lower || top1Lower === top3Lower || top2Lower === top3Lower) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pilihan Duplikat!',
                        text: 'Pilihan Top 1, 2, dan 3 harus berbeda.',
                        confirmButtonColor: '#4361ee'
                    });
                    return;
                }

                const form = this;
                Swal.fire({
                    title: 'Kirim Jawaban?',
                    text: 'Jawaban tidak dapat diubah setelah dikirim.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#22c55e',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearProgress(); // Clear saved progress on successful submit
                        form.submit();
                    }
                });
            });

            // Auto-save when top choices change
            ['top1', 'top2', 'top3', 'pekerjaan_lain'].forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('change', saveProgress);
                    input.addEventListener('blur', saveProgress);
                }
            });

            function lockCurrentGroup() {
                completedGroups.add(currentGroup);

                pageButtons[currentGroup].classList.add('completed', 'locked');
                pageButtons[currentGroup].classList.remove('active');

                // Disable sortable
                const kelompok = String.fromCharCode(65 + currentGroup);
                if (sortableInstances[kelompok]) {
                    sortableInstances[kelompok].option('disabled', true);
                }

                const groupPage = document.querySelector(`.group-page[data-group="${currentGroup}"]`);
                if (groupPage) {
                    groupPage.classList.add('locked');
                }

                saveProgress(); // Auto-save when group is locked
            }

            function goToGroup(groupIndex) {
                currentGroup = groupIndex;

                groupPages.forEach((page) => {
                    const pageGroup = page.dataset.group;
                    if (pageGroup === 'final' && groupIndex === totalGroups) {
                        page.classList.add('active');
                    } else if (parseInt(pageGroup) === groupIndex) {
                        page.classList.add('active');
                    } else {
                        page.classList.remove('active');
                    }
                });

                updateUI();
                saveProgress(); // Auto-save when navigating to new group
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }

            function updateUI() {
                pageButtons.forEach((btn) => {
                    const btnGroup = btn.dataset.group;
                    btn.classList.remove('active');

                    if (btnGroup === 'final') {
                        if (currentGroup === totalGroups) btn.classList.add('active');
                    } else {
                        const btnIdx = parseInt(btnGroup);
                        if (btnIdx === currentGroup && !completedGroups.has(btnIdx)) {
                            btn.classList.add('active');
                        }
                    }
                });

                if (currentGroup === totalGroups) {
                    btnNext.style.display = 'none';
                    btnSubmit.style.display = 'inline-block';
                } else {
                    btnNext.style.display = 'inline-block';
                    btnSubmit.style.display = 'none';

                    if (currentGroup === totalGroups - 1) {
                        btnNext.innerHTML = 'Lanjutkan ke Langkah Akhir<i class="fas fa-star ms-2"></i>';
                    } else {
                        const nextLetter = String.fromCharCode(66 + currentGroup);
                        btnNext.innerHTML =
                            `Lanjutkan ke Kelompok ${nextLetter}<i class="fas fa-arrow-right ms-2"></i>`;
                    }
                }

                const progress = ((currentGroup + 1) / (totalGroups + 1)) * 100;
                progressBar.style.width = progress + '%';

                if (currentGroup < totalGroups) {
                    const groupLetter = String.fromCharCode(65 + currentGroup);
                    progressText.textContent = `Kelompok ${groupLetter} (${currentGroup + 1}/${totalGroups + 1})`;
                } else {
                    progressText.textContent = `Langkah Terakhir (${totalGroups + 1}/${totalGroups + 1})`;
                }
            }

            // Dropdown job selection
            document.querySelectorAll('.job-option').forEach(option => {
                option.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.dataset.target;
                    const targetInput = document.getElementById(targetId);
                    if (targetInput) {
                        targetInput.value = this.textContent.trim();
                        saveProgress(); // Auto-save when dropdown selection made
                    }
                });
            });

            // Prevent back button
            history.pushState(null, null, location.href);
            window.onpopstate = function() {
                history.go(1);
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Dapat Kembali',
                    text: 'Anda tidak dapat kembali saat mengerjakan tes.',
                    confirmButtonColor: '#4361ee'
                });
            };

            // ============================================
            // RESTORE PROGRESS ON PAGE LOAD
            // ============================================
            const wasRestored = restoreProgress();
            if (wasRestored) {
                // Navigate to the saved current group
                groupPages.forEach((page) => {
                    const pageGroup = page.dataset.group;
                    if (pageGroup === 'final' && currentGroup === totalGroups) {
                        page.classList.add('active');
                    } else if (parseInt(pageGroup) === currentGroup) {
                        page.classList.add('active');
                    } else {
                        page.classList.remove('active');
                    }
                });

                // Show restoration notification
                Swal.fire({
                    icon: 'success',
                    title: 'Progress Dipulihkan!',
                    html: `<p>Jawaban sebelumnya telah dipulihkan.</p>
                           <p class="small text-muted">Anda sedang di <strong>Kelompok ${currentGroup < totalGroups ? String.fromCharCode(65 + currentGroup) : 'Akhir'}</strong></p>`,
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'Lanjutkan',
                    timer: 5000,
                    timerProgressBar: true
                });
            }

            updateUI();

            // ============================================
            // PERIODIC AUTO-SAVE & DISCONNECT HANDLING
            // ============================================

            // Auto-save every 30 seconds
            setInterval(saveProgress, 30000);

            // Save on page visibility change (tab switch, minimize)
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'hidden') {
                    saveProgress();
                }
            });

            // Save before page unload (close tab, refresh, navigate away)
            window.addEventListener('beforeunload', function(e) {
                saveProgress();
            });

            // Save on online/offline events
            window.addEventListener('offline', function() {
                saveProgress();
                Swal.fire({
                    icon: 'warning',
                    title: 'Koneksi Terputus',
                    text: 'Progress Anda telah disimpan secara lokal. Lanjutkan mengerjakan, jawaban akan tersimpan.',
                    confirmButtonColor: '#4361ee',
                    toast: true,
                    position: 'top-end',
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });

            window.addEventListener('online', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Koneksi Kembali',
                    text: 'Anda sudah online kembali.',
                    confirmButtonColor: '#22c55e',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            });
        });
    </script>
</body>

</html>
