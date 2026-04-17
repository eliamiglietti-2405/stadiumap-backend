<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\VerifyTwoFactorRequest;
use App\Models\LoginTwoFactorCode;
use App\Notifications\LoginOtpNotification;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'uid' => (string) Str::uuid(),
            'email' => $request->email,
            'password_hash' => Hash::make($request->password),
            'display_name' => $request->displayName,
            'photo_url' => null,
            'is_admin' => false,
            'last_login' => now(),
        ]);
        $token = $user->createToken('mobile')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user->load('favorites')),
        ], 201);
    }
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password_hash)) {
            return response()->json([
                'message' => 'Credenziali non valide.'
            ], 401);
        }

        $plainCode = (string) random_int(100000, 999999);
        $loginToken = (string) Str::uuid();

        LoginTwoFactorCode::where('user_id', $user->id)
            ->whereNull('used_at')
            ->delete();

        LoginTwoFactorCode::create([
            'user_id' => $user->id,
            'login_token' => $loginToken,
            'code_hash' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(10),
        ]);

        $user->notify(new LoginOtpNotification($plainCode));

        return response()->json([
            'requiresTwoFactor' => true,
            'loginToken' => $loginToken,
            'message' => 'Ti abbiamo inviato un codice di verifica via email.',
        ]);
    }
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('favorites');
        return response()->json([
            'user' => new UserResource($user),
        ]);
    }
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json([
            'message' => 'Logout eseguito con successo.'
        ]);
    }
    public function verifyTwoFactor(VerifyTwoFactorRequest $request): JsonResponse
    {
        $record = LoginTwoFactorCode::where('login_token', $request->loginToken)
            ->whereNull('used_at')
            ->first();

        if (! $record) {
            return response()->json([
                'message' => 'Richiesta 2FA non valida.'
            ], 404);
        }

        if ($record->expires_at->isPast()) {
            return response()->json([
                'message' => 'Codice scaduto.'
            ], 422);
        }

        if (! Hash::check($request->code, $record->code_hash)) {
            return response()->json([
                'message' => 'Codice non corretto.'
            ], 422);
        }

        $record->update([
            'used_at' => now(),
        ]);

        $user = $record->user;
        $user->last_login = now();
        $user->save();

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user->load('favorites')),
        ]);
    }
}