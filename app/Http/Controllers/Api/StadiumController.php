<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\StadiumResource;
use App\Models\Stadium;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class StadiumController extends Controller
{
    public function index(): JsonResponse
    {
        $stadiums = Stadium::query()
            ->orderBy('country')
            ->orderBy('city')
            ->orderBy('name')
            ->get();
            return response()->json([
            'data' => StadiumResource::collection($stadiums),
            ]);
    }
    public function show(Stadium $stadium): JsonResponse
    {
    return response()->json([
    'data' => new StadiumResource($stadium),
    ]);
    }
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));
        $stadiums = Stadium::query()
            ->when($query !== '', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
                ->orWhere('city', 'like', "%{$query}%")
                ->orWhere('country', 'like', "%{$query}%")
                ->orWhere('team_name', 'like', "%{$query}%");
        })
            ->orderBy('name')
            ->get();
            
        return response()->json([
            'data' => StadiumResource::collection($stadiums),
        ]);
    }
}
