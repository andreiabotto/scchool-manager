<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *     path="/api/generate_token",
     *     tags={"Authentication"},
     *     summary="Create token for user",
     *     description="Create token for user",
     *     @OA\RequestBody(
     *           required=true,
     *           @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="email", type="string", example="teste@email.com"),
     *               @OA\Property(property="password", type="string", example="password123")
     *           )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response",
     *          @OA\JsonContent(
     *               type="object",
     *               @OA\Property(property="token", type="string", example="438309489308450958"),
     *               @OA\Property(property="email", type="string", example="teste@email.com")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Invalid data"
     *      ),
     *      @OA\Response(
     *           response=500,
     *           description="Internal error"
     *       )
     * )
     */
    public function generate_token(LoginRequest $request)
    {
        try{
            $user = $this->authService->findUser($request->all());
            if($user) {
                $token = $user->createToken('API Token')->plainTextToken;

                return response()->json([
                    'token' => $token,
                    'user' => $user->email,
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'Credenciais inválidas.',
                ], 422);
            }
        }
        catch(\Exception $e){
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }
}
