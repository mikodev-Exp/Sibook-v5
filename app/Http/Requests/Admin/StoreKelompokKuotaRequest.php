<?php

namespace App\Http\Requests\Admin;

use App\Models\KelompokKuota;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreKelompokKuotaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Normalisasi input jika dikirim name/quota/active
        if ($this->has('name') && ! $this->has('nama')) {
            $this->merge(['nama' => $this->input('name')]);
        }
        if ($this->has('quota') && ! $this->has('kuota')) {
            $this->merge(['kuota' => $this->input('quota')]);
        }
        if ($this->has('active') && ! $this->has('aktif')) {
            $this->merge(['aktif' => $this->input('active')]);
        }
        if ($this->has('major_ids') && ! $this->has('jurusan_ids')) {
            $this->merge(['jurusan_ids' => $this->input('major_ids')]);
        }
    }

    public function rules(): array
    {
        return [
            'nama'          => ['required', 'string', 'max:100'],
            'kuota'         => ['required', 'integer', 'min:0'],
            'aktif'         => ['required', 'integer', 'min:0', 'lte:kuota'],
            'is_catch_all'  => ['nullable', 'boolean'],
            'urutan'        => ['nullable', 'integer'],
            'jurusan_ids'   => ['nullable', 'array'],
            'jurusan_ids.*' => ['exists:jurusans,id'],
            'major_ids'     => ['nullable', 'array'],
            'major_ids.*'   => ['exists:jurusans,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required'  => 'Nama kelompok kuota wajib diisi.',
            'kuota.required' => 'Total kuota wajib diisi.',
            'kuota.min'      => 'Total kuota tidak boleh bernilai negatif.',
            'aktif.required' => 'Jumlah magang aktif wajib diisi.',
            'aktif.min'      => 'Jumlah magang aktif tidak boleh bernilai negatif.',
            'aktif.lte'      => 'Jumlah mahasiswa magang aktif tidak boleh melebihi total kuota.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $isCatchAll = (bool) $this->input('is_catch_all');
            if ($isCatchAll) {
                $group = $this->route('group') ?? $this->route('kelompok_kuota');
                $groupId = is_object($group) ? $group->id : $group;

                $exists = KelompokKuota::where('is_catch_all', true)
                    ->when($groupId, fn ($q) => $q->where('id', '!=', $groupId))
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('is_catch_all', 'Hanya boleh ada satu kelompok penampung umum ("Lainnya").');
                }
            }
        });
    }
}
