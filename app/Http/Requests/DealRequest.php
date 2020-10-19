<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string company_name
 * @property string description
 * @property string notes
 * @property int status_id
 * @property int manager_id
 */
class DealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_name' => 'required|string|max:100',
            'description' => 'required|string|max:100',
            'notes' => 'string|max:100',
            'status_id' => 'required|numeric|min:0|max:5',
            'manager_id' => 'numeric|min:0|max:10',
        ];
    }
}
