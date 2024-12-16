<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntidadeRequest extends FormRequest
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
            'tabela' => 'required|string|min:6|unique:acl_entidades,tabela,' . $this->id,   // atendo nas vírgulas
            'model' => 'required|string|min:3|max:12|unique:acl_entidades,model,' . $this->id,   // atendo nas vírgulas
            'descricao' => 'required|min:4',            
            'ativo' => ['required','in:"SIM","NÃO"'],
        ];
    }
}
