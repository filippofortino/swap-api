<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUploadedFileRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'parent_folder' => json_decode($this->input('filepond'))->parent_folder,
            'file_name' => $this->file('filepond')->getClientOriginalName(),
            'file_hash' => hash_file('sha256', $this->file('filepond')->path())
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_folder' => 'required|integer|exists:folders,id',
            'filepond' => 'required|file',
            'file_name' => 'required|string',
            'file_hash' => 'size:64'
        ];
    }
}
