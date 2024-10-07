<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Responses\User\UserResponse;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns a list of users",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     */
    public function index(Request $request)
    {
        $params = $request->all();
        if($params) {
            $params = $params['values'];

            if (is_null($params[1])) {
                $params = [];
            }
        }

        $users = $this->userService->getAllUsers($params);
        return UserResponse::collection($users);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     description="Returns a user by ID",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *      @OA\Response(
     *           response=500,
     *           description="Internal error"
     *       )
     * )
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);

        if ($user) {
            return new UserResponse($user);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     description="Creates a new user",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *              @OA\Property(property="password", type="string", example="password"),
     *              @OA\Property(property="is_admin", type="bool", example="0")
     *          )
     *      ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Invalid data"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->createUser($request->validated());
        return new UserResponse($user);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Update an existing user",
     *     description="Updates an existing user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *               @OA\Property(property="name", type="string", example="John Doe"),
     *               @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *               @OA\Property(property="password", type="string", example="password"),
     *               @OA\Property(property="is_admin", type="bool", example="0")
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *      @OA\Response(
     *          response=422,
     *          description="Invalid data"
     *      ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal error"
     *     )
     * )
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $updated = $this->userService->updateUser($id, $request->validated());

        if ($updated) {
            $user = $this->userService->getUserById($id);
            return new UserResponse($user);
        }

        return response()->json(['message' => 'User not found'], 404);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Delete a user",
     *     description="Deletes a user",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found"
     *     ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     */
    public function destroy($id)
    {
        $deleted = $this->userService->deleteUser($id);

        if ($deleted) {
            return response()->json(['message' => 'User deleted successfully']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
}
