<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uid' => (string) $this->uid,
            'email' => $this->email,
            'displayName' => $this->display_name,
            'photoUrl' => $this->photo_url,
            'favoriteStadiums' => $this->whenLoaded(
            'favorites',
            fn () => $this->favorites->pluck('id')->map(fn ($id) => (string) $id)->values(),
            []
            ),
            'createdAt' => optional($this->created_at)?->toIso8601String(),
            'lastLogin' => optional($this->last_login)?->toIso8601String(),
            'isAdmin' => (bool) $this->is_admin,
        ];
    }
}
