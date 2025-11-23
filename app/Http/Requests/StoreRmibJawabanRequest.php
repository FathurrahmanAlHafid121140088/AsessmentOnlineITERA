<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\RmibPekerjaan;
use Illuminate\Support\Facades\Log;

class StoreRmibJawabanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // User harus login untuk submit jawaban
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validasi struktur jawaban
            'jawaban' => [
                'required',
                'array',
                'size:9', // Harus ada 9 kelompok (A-I)
            ],
            'jawaban.*' => [
                'required',
                'array',
                'size:12', // Setiap kelompok harus ada 12 pekerjaan
            ],
            'jawaban.*.*' => [
                'required',
                'integer',
                'min:1',
                'max:12',
            ],

            // Validasi Top 1/2/3
            'top1' => [
                'required',
                'string',
                'max:255',
            ],
            'top2' => [
                'required',
                'string',
                'max:255',
            ],
            'top3' => [
                'required',
                'string',
                'max:255',
            ],

            // Validasi pekerjaan lain (opsional)
            'pekerjaan_lain' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[a-zA-Z0-9\s,.\-\/]+$/u', // Hanya karakter aman
            ],
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'jawaban.required' => 'Data jawaban wajib diisi.',
            'jawaban.array' => 'Format data jawaban tidak valid.',
            'jawaban.size' => 'Harus ada tepat 9 kelompok pekerjaan (A-I).',

            'jawaban.*.required' => 'Semua kelompok pekerjaan wajib diisi.',
            'jawaban.*.array' => 'Format kelompok tidak valid.',
            'jawaban.*.size' => 'Setiap kelompok harus memiliki tepat 12 pekerjaan.',

            'jawaban.*.*.required' => 'Semua pekerjaan harus diberi peringkat.',
            'jawaban.*.*.integer' => 'Peringkat harus berupa angka.',
            'jawaban.*.*.min' => 'Peringkat minimal adalah 1.',
            'jawaban.*.*.max' => 'Peringkat maksimal adalah 12.',

            'top1.required' => 'Pilihan peringkat 1 wajib diisi.',
            'top1.string' => 'Format pilihan tidak valid.',
            'top1.max' => 'Nama pekerjaan terlalu panjang.',

            'top2.required' => 'Pilihan peringkat 2 wajib diisi.',
            'top2.string' => 'Format pilihan tidak valid.',
            'top2.max' => 'Nama pekerjaan terlalu panjang.',

            'top3.required' => 'Pilihan peringkat 3 wajib diisi.',
            'top3.string' => 'Format pilihan tidak valid.',
            'top3.max' => 'Nama pekerjaan terlalu panjang.',

            'pekerjaan_lain.max' => 'Pekerjaan lain maksimal 500 karakter.',
            'pekerjaan_lain.regex' => 'Pekerjaan lain hanya boleh berisi huruf, angka, dan karakter dasar.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Get data diri from route parameter
            $dataDiri = $this->route('data_diri');

            if (!$dataDiri) {
                $validator->errors()->add('data_diri', 'Data diri tidak ditemukan.');
                return;
            }

            $gender = $dataDiri->jenis_kelamin;

            // ========================================
            // VALIDASI CUSTOM 1: Uniqueness per Kelompok
            // ========================================
            $this->validateUniqueRanksPerGroup($validator);

            // ========================================
            // VALIDASI CUSTOM 2: Completeness (1-12 semua ada)
            // ========================================
            $this->validateCompleteRanksPerGroup($validator);

            // ========================================
            // VALIDASI CUSTOM 3: Top 1/2/3 Tidak Boleh Sama
            // ========================================
            $this->validateUniqueTopChoices($validator);

            // ========================================
            // VALIDASI CUSTOM 4: Pekerjaan Exists in Database
            // ========================================
            $this->validatePekerjaanExists($validator, $gender);

            // ========================================
            // VALIDASI CUSTOM 5: Top 1/2/3 Exists in Database
            // ========================================
            $this->validateTopChoicesExist($validator, $gender);

            // ========================================
            // SECURITY: Log Suspicious Activity
            // ========================================
            if ($validator->errors()->isNotEmpty()) {
                Log::warning('RMIB Form Validation Failed', [
                    'user_id' => auth()->id(),
                    'user_email' => auth()->user()->email,
                    'ip_address' => $this->ip(),
                    'user_agent' => $this->userAgent(),
                    'errors' => $validator->errors()->toArray(),
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
        });
    }

    /**
     * Validate that each group has unique ranks (no duplicates)
     */
    protected function validateUniqueRanksPerGroup(Validator $validator): void
    {
        $jawaban = $this->input('jawaban', []);

        foreach ($jawaban as $kelompok => $pekerjaanList) {
            if (!is_array($pekerjaanList)) {
                continue;
            }

            $ranks = array_values($pekerjaanList);
            $uniqueRanks = array_unique($ranks);

            if (count($ranks) !== count($uniqueRanks)) {
                $validator->errors()->add(
                    "jawaban.{$kelompok}",
                    "Kelompok {$kelompok}: Setiap angka 1-12 hanya boleh digunakan satu kali. Ada angka yang duplikat."
                );

                // Log suspicious activity
                Log::warning('Duplicate ranks detected in RMIB submission', [
                    'user_id' => auth()->id(),
                    'kelompok' => $kelompok,
                    'ranks' => $ranks,
                    'ip_address' => $this->ip(),
                ]);
            }
        }
    }

    /**
     * Validate that each group has complete ranks (1-12 all present)
     */
    protected function validateCompleteRanksPerGroup(Validator $validator): void
    {
        $jawaban = $this->input('jawaban', []);

        foreach ($jawaban as $kelompok => $pekerjaanList) {
            if (!is_array($pekerjaanList)) {
                continue;
            }

            // Convert to integers first (karena dari form input berupa string)
            $ranks = array_map('intval', array_values($pekerjaanList));
            sort($ranks, SORT_NUMERIC);

            $expectedRanks = range(1, 12);

            if ($ranks !== $expectedRanks) {
                $missingRanks = array_diff($expectedRanks, $ranks);
                $extraRanks = array_diff($ranks, $expectedRanks);

                $errorMsg = "Kelompok {$kelompok}: Harus menggunakan semua angka 1-12 tanpa ada yang terlewat.";

                if (!empty($missingRanks)) {
                    $errorMsg .= " Angka yang hilang: " . implode(', ', $missingRanks) . ".";
                }

                if (!empty($extraRanks)) {
                    $errorMsg .= " Angka yang tidak valid: " . implode(', ', $extraRanks) . ".";
                }

                $validator->errors()->add("jawaban.{$kelompok}", $errorMsg);

                // Log suspicious activity
                Log::warning('Incomplete ranks detected in RMIB submission', [
                    'user_id' => auth()->id(),
                    'kelompok' => $kelompok,
                    'missing_ranks' => $missingRanks,
                    'extra_ranks' => $extraRanks,
                    'ip_address' => $this->ip(),
                ]);
            }
        }
    }

    /**
     * Validate that Top 1/2/3 choices are different
     */
    protected function validateUniqueTopChoices(Validator $validator): void
    {
        $top1 = $this->input('top1');
        $top2 = $this->input('top2');
        $top3 = $this->input('top3');

        $topChoices = array_filter([$top1, $top2, $top3]);
        $uniqueChoices = array_unique($topChoices);

        if (count($topChoices) !== count($uniqueChoices)) {
            $validator->errors()->add(
                'top1',
                'Pilihan Top 1, Top 2, dan Top 3 tidak boleh sama. Harap pilih 3 pekerjaan yang berbeda.'
            );

            // Log suspicious activity
            Log::warning('Duplicate top choices in RMIB submission', [
                'user_id' => auth()->id(),
                'top_choices' => ['top1' => $top1, 'top2' => $top2, 'top3' => $top3],
                'ip_address' => $this->ip(),
            ]);
        }
    }

    /**
     * Validate that all submitted pekerjaan exist in database
     */
    protected function validatePekerjaanExists(Validator $validator, string $gender): void
    {
        $jawaban = $this->input('jawaban', []);

        // Get valid pekerjaan from database
        $validPekerjaan = RmibPekerjaan::where('gender', $gender)
            ->pluck('nama_pekerjaan')
            ->toArray();

        foreach ($jawaban as $kelompok => $pekerjaanList) {
            if (!is_array($pekerjaanList)) {
                continue;
            }

            foreach (array_keys($pekerjaanList) as $namaPekerjaan) {
                if (!in_array($namaPekerjaan, $validPekerjaan, true)) {
                    $validator->errors()->add(
                        "jawaban.{$kelompok}",
                        "Kelompok {$kelompok}: Pekerjaan '{$namaPekerjaan}' tidak valid atau tidak ditemukan dalam database."
                    );

                    // Log CRITICAL security issue - possible injection attempt
                    Log::critical('Invalid pekerjaan submitted - Possible attack attempt', [
                        'user_id' => auth()->id(),
                        'user_email' => auth()->user()->email,
                        'kelompok' => $kelompok,
                        'invalid_pekerjaan' => $namaPekerjaan,
                        'ip_address' => $this->ip(),
                        'user_agent' => $this->userAgent(),
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                }
            }
        }
    }

    /**
     * Validate that Top 1/2/3 choices exist in database
     */
    protected function validateTopChoicesExist(Validator $validator, string $gender): void
    {
        $topChoices = [
            'top1' => $this->input('top1'),
            'top2' => $this->input('top2'),
            'top3' => $this->input('top3'),
        ];

        // Get valid pekerjaan from database
        $validPekerjaan = RmibPekerjaan::where('gender', $gender)
            ->pluck('nama_pekerjaan')
            ->toArray();

        foreach ($topChoices as $field => $choice) {
            if (empty($choice)) {
                continue;
            }

            if (!in_array($choice, $validPekerjaan, true)) {
                $validator->errors()->add(
                    $field,
                    "Pilihan pekerjaan '{$choice}' tidak valid atau tidak ditemukan dalam database."
                );

                // Log CRITICAL security issue
                Log::critical('Invalid top choice submitted - Possible attack attempt', [
                    'user_id' => auth()->id(),
                    'user_email' => auth()->user()->email,
                    'field' => $field,
                    'invalid_choice' => $choice,
                    'ip_address' => $this->ip(),
                    'user_agent' => $this->userAgent(),
                    'timestamp' => now()->toDateTimeString(),
                ]);
            }
        }
    }

    /**
     * Prepare input for validation (Sanitization)
     */
    protected function prepareForValidation(): void
    {
        // Sanitize pekerjaan_lain untuk mencegah XSS
        if ($this->has('pekerjaan_lain')) {
            $pekerjaanLain = $this->input('pekerjaan_lain');

            // Strip HTML tags
            $pekerjaanLain = strip_tags($pekerjaanLain);

            // Convert special characters to HTML entities
            $pekerjaanLain = htmlspecialchars($pekerjaanLain, ENT_QUOTES, 'UTF-8');

            // Trim whitespace
            $pekerjaanLain = trim($pekerjaanLain);

            $this->merge([
                'pekerjaan_lain' => $pekerjaanLain,
            ]);
        }
    }
}
