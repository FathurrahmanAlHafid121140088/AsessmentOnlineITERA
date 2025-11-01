<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataDiriRequest extends FormRequest
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
        return [
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'provinsi' => 'required|string|max:255',
            'alamat' => 'required|string',
            'usia' => 'required|integer|min:1',
            'fakultas' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'asal_sekolah' => 'required|string|max:255',
            'status_tinggal' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'keluhan' => 'required|string',
            'lama_keluhan' => 'required|string|max:255',
            'pernah_konsul' => 'required|in:Ya,Tidak',
            'pernah_tes' => 'required|in:Ya,Tidak',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama lengkap',
            'jenis_kelamin' => 'jenis kelamin',
            'provinsi' => 'provinsi',
            'alamat' => 'alamat',
            'usia' => 'usia',
            'fakultas' => 'fakultas',
            'program_studi' => 'program studi',
            'asal_sekolah' => 'asal sekolah',
            'status_tinggal' => 'status tinggal',
            'email' => 'email',
            'keluhan' => 'keluhan',
            'lama_keluhan' => 'lama keluhan',
            'pernah_konsul' => 'pernah konsultasi',
            'pernah_tes' => 'pernah tes',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email' => ':attribute harus berformat email yang valid.',
            'integer' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max karakter.',
            'in' => ':attribute yang dipilih tidak valid.',
        ];
    }
}
