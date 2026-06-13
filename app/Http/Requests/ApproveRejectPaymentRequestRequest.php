<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ApproveRejectPaymentRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('get')) {
            return true;
        }

        return $this->user()?->isFinance() ?? false;
    }

    public function rules(): array
    {
        return [
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
