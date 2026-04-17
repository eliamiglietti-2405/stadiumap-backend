<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class UserController extends Controller
{
 public function me(Request $request): JsonResponse
 {
 $user = $request->user()->load('favorites');
 return response()->json([
 'user' => new UserResource($user),
 ]);
 }
}