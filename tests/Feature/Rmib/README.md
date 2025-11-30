# Dokumentasi PHPUnit Test - Tes Peminatan Karir RMIB

## Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Struktur Folder](#struktur-folder)
3. [Cara Menjalankan Test](#cara-menjalankan-test)
4. [Detail Test Files](#detail-test-files)
   - [RmibControllerTest](#1-rmibcontrollertestphp)
   - [RmibModelsTest](#2-rmibmodelstestphp)
   - [RmibScoringServiceTest](#3-rmibscoringservicetestphp)
   - [RmibValidationTest](#4-rmibvalidationtestphp)
   - [RmibWorkflowIntegrationTest](#5-rmibworkflowintegrationtestphp)
5. [Dependencies](#dependencies)
6. [Troubleshooting](#troubleshooting)

---

## Pendahuluan

Dokumentasi ini menjelaskan unit test dan integration test untuk fitur **Tes Peminatan Karir RMIB (Rothwell-Miller Interest Blank)** pada aplikasi Assessment Online.

### Apa itu RMIB?

RMIB adalah tes psikologi yang mengukur minat seseorang terhadap 12 kategori pekerjaan:

| No | Kategori | Singkatan | Deskripsi |
|----|----------|-----------|-----------|
| 1 | Outdoor | OUT | Minat aktivitas luar ruangan |
| 2 | Mechanical | MECH | Minat mesin dan teknologi mekanik |
| 3 | Computational | COMP | Minat perhitungan dan analisis data |
| 4 | Scientific | SCI | Minat penelitian dan eksperimen ilmiah |
| 5 | Personal Contact | PERS | Minat interaksi dan hubungan interpersonal |
| 6 | Aesthetic | AETH | Minat seni dan estetika |
| 7 | Literary | LIT | Minat membaca dan menulis |
| 8 | Musical | MUS | Minat musik dan ekspresi musikal |
| 9 | Social Service | S.S | Minat pelayanan sosial |
| 10 | Clerical | CLER | Minat administratif dan dokumentasi |
| 11 | Practical | PRAC | Minat pekerjaan praktis hands-on |
| 12 | Medical | MED | Minat bidang kesehatan |

### Struktur Tes RMIB

- **9 Kelompok Pekerjaan** (A-I)
- **12 Pekerjaan per Kelompok**
- **Total 108 Pekerjaan** yang harus di-ranking
- Menggunakan metode **Circular Shift** untuk distribusi kategori

---

## Struktur Folder

```
tests/Feature/Rmib/
├── README.md                          # Dokumentasi ini
├── RmibControllerTest.php             # Test untuk KarirController
├── RmibModelsTest.php                 # Test untuk Models (KarirDataDiri, RmibHasilTes, dll)
├── RmibScoringServiceTest.php         # Test untuk RmibScoringService
├── RmibValidationTest.php             # Test untuk validasi form dan request
└── RmibWorkflowIntegrationTest.php    # Test integrasi workflow lengkap
```

---

## Cara Menjalankan Test

### Menjalankan Semua Test RMIB

```bash
php artisan test --filter=Rmib
```

### Menjalankan Test Spesifik

```bash
# Test Controller
php artisan test tests/Feature/Rmib/RmibControllerTest.php

# Test Models
php artisan test tests/Feature/Rmib/RmibModelsTest.php

# Test Scoring Service
php artisan test tests/Feature/Rmib/RmibScoringServiceTest.php

# Test Validation
php artisan test tests/Feature/Rmib/RmibValidationTest.php

# Test Integration Workflow
php artisan test tests/Feature/Rmib/RmibWorkflowIntegrationTest.php
```

### Menjalankan dengan Output Verbose

```bash
php artisan test --filter=Rmib -v
```

### Menjalankan Test Method Tertentu

```bash
php artisan test --filter=test_complete_user_workflow
```

---

## Detail Test Files

### 1. RmibControllerTest.php

File ini menguji semua endpoint di `KarirController.php`.

#### User Side Tests

| Method | Deskripsi | Route |
|--------|-----------|-------|
| `test_user_can_access_data_diri_page` | Memastikan user dapat mengakses halaman pengisian data diri | `karir.datadiri` |
| `test_user_can_store_data_diri` | Memastikan user dapat menyimpan data diri ke database | `karir.datadiri.store` |
| `test_store_data_diri_validation_fails_when_required_fields_missing` | Memastikan validasi gagal jika field required kosong | `karir.datadiri.store` |
| `test_user_can_access_tes_form` | Memastikan user dapat mengakses form tes RMIB | `karir.tes.form` |
| `test_user_can_view_interpretasi_page` | Memastikan user dapat melihat hasil interpretasi | `karir.hasil` |
| `test_user_dashboard_displays_correct_data` | Memastikan dashboard user menampilkan data dengan benar | `karir.user.dashboard` |

#### Admin Side Tests

| Method | Deskripsi | Route |
|--------|-----------|-------|
| `test_admin_can_access_karir_index` | Memastikan admin dapat mengakses halaman daftar hasil tes | `admin.karir.index` |
| `test_admin_can_view_hasil_detail` | Memastikan admin dapat melihat detail hasil tes dengan matrix | `admin.karir.detail` |
| `test_admin_can_delete_hasil_tes` | Memastikan admin dapat menghapus hasil tes | `admin.karir.destroy` |
| `test_admin_can_search_hasil_tes` | Memastikan fitur search bekerja dengan benar | `admin.karir.index` |
| `test_admin_can_access_provinsi_page` | Memastikan admin dapat mengakses halaman statistik provinsi | `admin.karir.provinsi` |
| `test_admin_can_access_program_studi_page` | Memastikan admin dapat mengakses halaman statistik prodi | `admin.karir.program-studi` |
| `test_admin_can_export_to_excel` | Memastikan fitur export Excel bekerja | `admin.karir.export` |

---

### 2. RmibModelsTest.php

File ini menguji model Eloquent dan relasinya.

#### KarirDataDiri Tests

| Method | Deskripsi |
|--------|-----------|
| `test_karir_data_diri_can_be_created` | Memastikan model dapat dibuat dengan fillable attributes |
| `test_karir_data_diri_has_many_hasil_tes` | Memastikan relasi hasMany ke RmibHasilTes bekerja |
| `test_karir_data_diri_update_or_create` | Memastikan updateOrCreate bekerja (tidak duplikat NIM) |

#### RmibHasilTes Tests

| Method | Deskripsi |
|--------|-----------|
| `test_rmib_hasil_tes_can_be_created` | Memastikan model dapat dibuat |
| `test_rmib_hasil_tes_belongs_to_karir_data_diri` | Memastikan relasi belongsTo bekerja (termasuk alias) |

#### RmibJawabanPeserta Tests

| Method | Deskripsi |
|--------|-----------|
| `test_rmib_jawaban_peserta_can_be_created` | Memastikan model dapat dibuat |
| `test_rmib_jawaban_peserta_bulk_insert` | Memastikan bulk insert 108 jawaban sekaligus bekerja |

#### RmibPekerjaan Tests

| Method | Deskripsi |
|--------|-----------|
| `test_rmib_pekerjaan_seeder_creates_correct_data` | Memastikan seeder membuat 216 pekerjaan (108 pria + 108 wanita) |
| `test_rmib_pekerjaan_has_twelve_categories` | Memastikan ada 12 kategori pekerjaan |
| `test_circular_shift_pattern_exists` | Memastikan pola circular shift di database benar |
| `test_different_jobs_for_male_and_female` | Memastikan ada perbedaan pekerjaan untuk pria/wanita |

---

### 3. RmibScoringServiceTest.php

File ini menguji `RmibScoringService.php` yang menghitung skor RMIB.

#### Skor Kategori Tests

| Method | Deskripsi |
|--------|-----------|
| `test_hitung_semua_skor_returns_correct_structure` | Memastikan return value memiliki struktur yang benar |
| `test_all_categories_present_in_result` | Memastikan semua 12 kategori ada di hasil |
| `test_skor_is_sum_of_rankings` | Memastikan skor adalah total ranking per kategori |

#### Peringkat Tests

| Method | Deskripsi |
|--------|-----------|
| `test_peringkat_is_sequential` | Memastikan peringkat berurutan 1-12 |
| `test_lower_score_gets_better_rank` | Memastikan skor rendah = peringkat tinggi (rank 1) |
| `test_tie_breaker_uses_alphabetical_order` | Memastikan tie-breaker menggunakan urutan alfabet |

#### Matrix Generation Tests

| Method | Deskripsi |
|--------|-----------|
| `test_matrix_generation_returns_correct_structure` | Memastikan struktur matrix benar |
| `test_matrix_has_twelve_rows` | Memastikan matrix memiliki 12 baris (kategori) |
| `test_matrix_has_nine_columns` | Memastikan matrix memiliki 9 kolom (kelompok A-I) |
| `test_circular_shift_pattern_in_matrix` | Memastikan pola circular shift di matrix benar |
| `test_matrix_sum_calculation` | Memastikan perhitungan SUM benar |
| `test_matrix_percentage_calculation` | Memastikan perhitungan persentase benar |

#### Skor Konsistensi Tests

| Method | Deskripsi |
|--------|-----------|
| `test_skor_konsistensi_in_valid_range` | Memastikan skor konsistensi dalam range 0-10 |
| `test_consistent_answers_give_high_score` | Memastikan jawaban konsisten menghasilkan skor tinggi |

#### Interpretasi Tests

| Method | Deskripsi |
|--------|-----------|
| `test_generate_interpretasi_returns_string` | Memastikan interpretasi berupa string |
| `test_interpretasi_contains_ranking` | Memastikan interpretasi berisi ranking 1, 2, 3 |

#### Deskripsi Kategori Tests

| Method | Deskripsi |
|--------|-----------|
| `test_get_deskripsi_kategori_returns_all_categories` | Memastikan semua 12 kategori ada |
| `test_each_category_has_required_fields` | Memastikan setiap kategori punya singkatan, nama, deskripsi |

---

### 4. RmibValidationTest.php

File ini menguji validasi input pada `StoreRmibJawabanRequest.php`.

#### Data Diri Validation

| Method | Deskripsi |
|--------|-----------|
| `test_nama_is_required` | Field nama wajib diisi |
| `test_jenis_kelamin_must_be_valid` | Jenis kelamin harus valid |
| `test_usia_must_be_positive_integer` | Usia harus integer positif |
| `test_prodi_sesuai_keinginan_must_be_valid` | Prodi sesuai keinginan harus "Ya" atau "Tidak" |

#### Jawaban RMIB Validation

| Method | Deskripsi |
|--------|-----------|
| `test_jawaban_is_required` | Field jawaban wajib diisi |
| `test_must_have_nine_groups` | Harus ada tepat 9 kelompok (A-I) |
| `test_each_group_must_have_twelve_jobs` | Setiap kelompok harus ada 12 pekerjaan |
| `test_rank_must_be_between_one_and_twelve` | Ranking harus 1-12 |
| `test_ranks_must_be_unique_per_group` | Ranking tidak boleh duplikat per kelompok |
| `test_all_ranks_must_be_used_per_group` | Semua angka 1-12 harus digunakan per kelompok |

#### Top 1/2/3 Validation

| Method | Deskripsi |
|--------|-----------|
| `test_top1_is_required` | Top 1 wajib dipilih |
| `test_top_choices_must_be_different` | Top 1, 2, 3 tidak boleh sama |
| `test_top_choices_must_exist_in_database` | Pilihan harus ada di database |

#### Pekerjaan Lain Validation

| Method | Deskripsi |
|--------|-----------|
| `test_pekerjaan_lain_is_optional` | Pekerjaan lain bersifat opsional |
| `test_pekerjaan_lain_max_length` | Maksimal 500 karakter |
| `test_pekerjaan_lain_sanitization` | XSS/script injection ditolak |

#### Security Tests

| Method | Deskripsi |
|--------|-----------|
| `test_unauthenticated_user_cannot_submit` | User tanpa login tidak bisa submit |
| `test_invalid_job_names_are_rejected` | Nama pekerjaan palsu ditolak |

---

### 5. RmibWorkflowIntegrationTest.php

File ini menguji alur lengkap dari awal hingga akhir.

#### Full Workflow Tests

| Method | Deskripsi |
|--------|-----------|
| `test_complete_user_workflow` | Test alur lengkap: data diri → tes → hasil |
| `test_user_can_retake_test` | Test user dapat mengulang tes |
| `test_scoring_calculated_after_submission` | Test skor dihitung setelah submit |

#### Admin Workflow Tests

| Method | Deskripsi |
|--------|-----------|
| `test_admin_workflow` | Test alur admin: view → search → export → delete |

#### Gender Specific Tests

| Method | Deskripsi |
|--------|-----------|
| `test_male_user_gets_male_jobs` | User pria mendapat pekerjaan pria |
| `test_female_user_gets_female_jobs` | User wanita mendapat pekerjaan wanita |

#### Data Integrity Tests

| Method | Deskripsi |
|--------|-----------|
| `test_data_integrity` | Memastikan semua data ter-link dengan benar |
| `test_transaction_rollback_on_error` | Memastikan rollback jika ada error |

---

## Dependencies

### Required Seeders

Test membutuhkan seeder berikut untuk berjalan:

```php
$this->seed(\Database\Seeders\RmibPekerjaanSeeder::class);
```

### Required Models

- `App\Models\Users`
- `App\Models\Admin`
- `App\Models\KarirDataDiri`
- `App\Models\RmibHasilTes`
- `App\Models\RmibJawabanPeserta`
- `App\Models\RmibPekerjaan`

### Required Services

- `App\Services\RmibScoringService`

### Required Routes

Test mengasumsikan routes berikut sudah terdefinisi:

**User Routes:**
- `karir.datadiri`
- `karir.datadiri.store`
- `karir.tes.form`
- `karir.tes.store`
- `karir.hasil`
- `karir.user.dashboard`

**Admin Routes:**
- `admin.karir.index`
- `admin.karir.detail`
- `admin.karir.destroy`
- `admin.karir.provinsi`
- `admin.karir.program-studi`
- `admin.karir.export`

---

## Troubleshooting

### Error: "Class RmibPekerjaanSeeder not found"

Pastikan seeder sudah ada di `database/seeders/RmibPekerjaanSeeder.php`.

### Error: "Route [xxx] not defined"

Pastikan semua routes sudah terdefinisi di `routes/web.php`.

### Error: "Table xxx doesn't exist"

Jalankan migrasi terlebih dahulu:

```bash
php artisan migrate:fresh
```

### Test Gagal karena Data Existing

Test menggunakan `RefreshDatabase` trait yang akan me-reset database sebelum setiap test. Pastikan tidak ada data production di database test.

### Slow Tests

Untuk mempercepat test, gunakan database SQLite in-memory:

```php
// phpunit.xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

---

## Kontributor

- Test dibuat untuk proyek Assessment Online ITERA
- Tanggal pembuatan: 24 November 2025

---

## Changelog

### v1.0.0 (2025-11-24)
- Initial release
- 5 test files dengan total ~76 test methods
- Coverage: Controller, Models, Service, Validation, Integration
