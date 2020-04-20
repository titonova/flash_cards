<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CardCreateForm extends FormRequest
{
    protected $_reqObj;

    /**
     * @param  \Illuminate\Http\Request $reqObj
     */
    public function __construct(Request $reqObj)
    {
        $this->_reqObj = $reqObj;

        parent::__construct();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category' => 'required|numeric',
            'problem' => 'string|required|max:250',
            'solution' => 'string|required|max:250',                        
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator( $validator )
    {
        $validator->after(function ($validator) {
            $formErrors = $validator->errors();
            $errorMsg = '';

            foreach ($formErrors->all() as $errMsg) {
                $errorMsg .= '<li>' . $errMsg . '</li>';
            }

            if (strlen($errorMsg) > 0) {
                $this->_reqObj->session()->put(
                    'error', 
                    '<ul>' . $errorMsg . '</ul>'
                ); 
            }           
        });
    }    
}
