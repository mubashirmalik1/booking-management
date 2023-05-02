<?php

namespace App\Http\Requests;

use App\Rules\MaxClientsPerSlot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $maxClientsPerSlotRule = new MaxClientsPerSlot(
            $this->input('service_id'),
            $this->input('booking_date'),
            $this->input('start_time'),
            $this->input('end_time')
        );

        return [
            'service_id' => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'first_name' => ['required', 'array', $maxClientsPerSlotRule],
            'first_name.*' => 'required|max:255',
            'last_name' => ['required', 'array', $maxClientsPerSlotRule],
            'last_name.*' => 'required|max:255',
            'email' => ['required', 'array', $maxClientsPerSlotRule],
            'email.*' => 'required|email|max:255',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'errors' => $validator->errors()
        ], 422);

        throw new HttpResponseException($response);
    }
}
