<?php

namespace App\Http\Requests\Car;

use App\Http\Requests\AbstractRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class GetRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return $this->paginateRules();
    }

    public function messages(): array
    {
        return $this->paginateMessages();
    }
}
