<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHasilKuesionerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization sudah dicek di middleware 'auth' di route
        // Jadi kita return true di sini
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nim' => 'required|string|max:20',
        ];

        // Validasi untuk 38 pertanyaan kuesioner
        // Setiap pertanyaan harus diisi dan nilainya antara 0-6
        // (0 untuk edge case testing, 1-6 adalah skala Likert normal)
        for ($i = 1; $i <= 38; $i++) {
            $rules["question{$i}"] = 'required|integer|min:0|max:6';
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        $messages = [
            'nim.required' => 'NIM wajib diisi.',
            'nim.string' => 'NIM harus berupa teks.',
            'nim.max' => 'NIM maksimal 20 karakter.',
        ];

        // Custom messages untuk setiap pertanyaan
        for ($i = 1; $i <= 38; $i++) {
            $messages["question{$i}.required"] = "Pertanyaan nomor {$i} wajib dijawab.";
            $messages["question{$i}.integer"] = "Jawaban pertanyaan nomor {$i} harus berupa angka.";
            $messages["question{$i}.min"] = "Jawaban pertanyaan nomor {$i} minimal 0.";
            $messages["question{$i}.max"] = "Jawaban pertanyaan nomor {$i} maksimal 6.";
        }

        return $messages;
    }
}
