<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RotaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;    //true = não necessita estar logado
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'entidade_id' => 'required',
            'rota' => 'required|min:4',            
            'descricao' => 'required|min:4',            
        ];
    }
}
