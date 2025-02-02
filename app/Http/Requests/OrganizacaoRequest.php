<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizacaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;                // padrão é true = não necessita estar logado
        return auth()->check();     // Verifica se o usuário está autenticado    
        // return auth()->user() && auth()->user()->role === 'admin';  // Apenas usuários admin    
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|min:6',
            'sigla' => 'required|string|min:3|max:20|unique:acl_organizacaos,sigla,' . $this->id,   // atendo nas vírgulas
            'descricao' => 'required|min:6',            
            'ativo' => ['required','in:"SIM","NÃO"'],
        ];
    }
}
