<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Favorite\StoreFavoriteRequest;
use App\Http\Resources\StadiumResource;
use App\Models\Stadium;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class FavoriteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $stadiums = $request->user()
            ->favorites()
            ->orderBy('name')
            ->get();
        return response()->json([
            'data' => StadiumResource::collection($stadiums),
        ]);
    }
    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        $request->user()
            ->favorites()
            ->syncWithoutDetaching([(int) $request->stadiumId]);
        return response()->json([
            'message' => 'Preferito aggiunto con successo.'
        ], 201);
    }
    public function destroy(Request $request, Stadium $stadium): JsonResponse
    {
        $request->user()->favorites()->detach($stadium->id);
        return response()->json([
            'message' => 'Preferito rimosso con successo.'
        ]);
    }
    public function check(Request $request, Stadium $stadium): JsonResponse
    {
        $isFavorite = $request->user()
            ->favorites()
            ->where('stadiums.id', $stadium->id)
            ->exists();
        return response()->json([
            'isFavorite' => $isFavorite,
        ]);
    }
}
