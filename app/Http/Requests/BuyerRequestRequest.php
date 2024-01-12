<?php

namespace App\Http\Requests;

use App\Models\BuyerRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class BuyerRequestRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "type_id" => "required|numeric",
            "service_id" => "required|numeric",
            "city_id" => "required|numeric",
            "street_id" => "nullable|numeric",
            "product_type_id" => "nullable|numeric",
            "image" => "nullable|file|mimes:png,jpg,svg|max:30000",
            "description" => "nullable|string",
        ];
    }

    private function storeImage()
    {
        $image = $this->file('image');

        if ($image instanceof UploadedFile) {
            return $image->store('buyer-requests', 'public');
        }

        return null;
    }

    public function storeBuyerRequest(): BuyerRequest
    {
        $path = $this->storeImage();

        return BuyerRequest::create(
            array_merge(
                $this->except('image', 'type_id'),
                ['image' => $path, 'service_type_id' => $this->type_id]
            )
        );
    }
    
}
