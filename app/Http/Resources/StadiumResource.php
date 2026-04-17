<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class StadiumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'country' => $this->country,
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
            'capacity' => (int) $this->capacity,
            'photoUrl' => $this->photo_url,
            'teamId' => (int) $this->team_id,
            'teamName' => $this->team_name,
            'yearBuilt' => (int) $this->year_built,
        ];
    }
}
