<?php
namespace App\Http\Requests\Favorite;
use Illuminate\Foundation\Http\FormRequest;
class StoreFavoriteRequest extends FormRequest
{
 public function authorize(): bool
 {
 return true;
 }
 public function rules(): array
 {
 return [
 'stadiumId' => ['required', 'integer', 'exists:stadiums,id'],
 ];
 }
}
