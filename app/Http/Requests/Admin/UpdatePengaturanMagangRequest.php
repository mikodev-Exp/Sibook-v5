<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengaturanMagangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('requirements') && ! $this->has('persyaratan')) {
            $this->merge(['persyaratan' => $this->input('requirements')]);
        }
        if ($this->has('requirements_note') && ! $this->has('catatan_persyaratan')) {
            $this->merge(['catatan_persyaratan' => $this->input('requirements_note')]);
        }
        if ($this->has('status_note') && ! $this->has('catatan_status')) {
            $this->merge(['catatan_status' => $this->input('status_note')]);
        }
        if ($this->has('opens_at') && ! $this->has('tanggal_buka')) {
            $this->merge(['tanggal_buka' => $this->input('opens_at')]);
        }
    }

    public function rules(): array
    {
        return [
            'status'              => ['required', 'in:open,soon,closed'],
            'catatan_status'      => ['nullable', 'string', 'max:500'],
            'status_note'         => ['nullable', 'string', 'max:500'],
            'tanggal_buka'        => ['nullable', 'date'],
            'opens_at'            => ['nullable', 'date'],
            'persyaratan'         => ['nullable', 'array'],
            'persyaratan.*'       => ['nullable', 'string', 'max:255'],
            'requirements'        => ['nullable', 'array'],
            'requirements.*'      => ['nullable', 'string', 'max:255'],
            'catatan_persyaratan' => ['nullable', 'string', 'max:1000'],
            'requirements_note'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status pembukaan magang wajib dipilih.',
            'status.in'       => 'Pilihan status tidak valid.',
        ];
    }
}
