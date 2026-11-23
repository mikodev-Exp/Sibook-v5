<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkSpsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('links') && ! $this->has('tautan')) {
            $this->merge(['tautan' => $this->input('links')]);
        }
        if ($this->has('tautan') && ! $this->has('links')) {
            $this->merge(['links' => $this->input('tautan')]);
        }
    }

    public function rules(): array
    {
        return [
            'tautan'          => ['nullable', 'array'],
            'tautan.*.label'  => ['required_with:tautan', 'string', 'max:100'],
            'tautan.*.url'    => ['required_with:tautan', 'url', 'max:255'],
            'links'           => ['nullable', 'array'],
            'links.*.label'   => ['required_with:links', 'string', 'max:100'],
            'links.*.url'     => ['required_with:links', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'tautan.*.label.required_with' => 'Nama label tautan SPS wajib diisi.',
            'tautan.*.url.required_with'   => 'URL tautan SPS wajib diisi.',
            'tautan.*.url.url'             => 'Format URL tautan SPS tidak valid (harus menyertakan http:// atau https://).',
            'links.*.label.required_with'  => 'Nama label tautan SPS wajib diisi.',
            'links.*.url.required_with'    => 'URL tautan SPS wajib diisi.',
            'links.*.url.url'              => 'Format URL tautan SPS tidak valid (harus menyertakan http:// atau https://).',
        ];
    }
}
