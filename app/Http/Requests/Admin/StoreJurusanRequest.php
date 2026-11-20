<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJurusanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Kompatibilitas nama / name
        if ($this->has('name') && ! $this->has('nama')) {
            $this->merge(['nama' => $this->input('name')]);
        }
        if ($this->has('nama') && ! $this->has('name')) {
            $this->merge(['name' => $this->input('nama')]);
        }
        if ($this->has('quota_group_id') && ! $this->has('kelompok_kuota_id')) {
            $this->merge(['kelompok_kuota_id' => $this->input('quota_group_id')]);
        }
    }

    public function rules(): array
    {
        $jurusan = $this->route('jurusan') ?? $this->route('major');
        $jurusanId = is_object($jurusan) ? $jurusan->id : $jurusan;

        return [
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jurusans', 'nama')->ignore($jurusanId),
            ],
            'name'              => ['nullable', 'string', 'max:100'],
            'kelompok_kuota_id' => ['nullable', 'exists:kelompok_kuotas,id'],
            'quota_group_id'    => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama jurusan / program studi wajib diisi.',
            'nama.unique'   => 'Nama jurusan sudah terdaftar sebelumnya.',
            'nama.max'      => 'Nama jurusan maksimal 100 karakter.',
        ];
    }
}
