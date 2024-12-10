<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VeiculoRequest extends FormRequest
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
        // todas as regras
        $rules = [
            // 'id' => 'required',
            'descricao' => 'required|min:4',        
            'tipo' => ['required','in:"Automóvel","Van","Micrônibus","Ônibus"'],
            'marca_modelo' => 'required|min:4',
            'capacidade' => 'required',
            'motorista' => '',
            'telefone' => 'required',
            'observacao' => '',            
            'ativo' => ['required','in:"SIM","NÃO"'],
        ];

        // vamos aplicar a validação apenas nos campos enviados no patch
        if ($this->isMethod('patch')) {

            $keys = array_keys($rules);
            $fieldsToUpdate = $this->only($keys);
            $fieldsRulesToUpdate = array_intersect_key($rules, array_flip(array_keys($fieldsToUpdate)));

            dd( 'API método PATCH',
                $rules ,
                $fieldsToUpdate ,
                $fieldsRulesToUpdate
            );

            return $fieldsRulesToUpdate;
        }     
        
        return $rules;
    }
}
