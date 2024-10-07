<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;
use App\Http\Responses\Classroom\ClassroomResponse;
use App\Services\Interfaces\ClassroomServiceInterface;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    protected ClassroomServiceInterface $classroomService;

    public function __construct(ClassroomServiceInterface $classroomService)
    {
        $this->classroomService = $classroomService;
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms",
     *     tags={"Classrooms"},
     *     summary="Get a list of classrooms",
     *     description="Returns a list of classrooms",
     *     security={{"sanctum": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successful response"
     *      ),
     *      @OA\Response(
     *           response=500,
     *           description="Internal error"
     *       )
     * )
     */
    public function index(Request $request)
    {
        try {
            $params = $request->all();
            if($params) {
                $params = $params['values'];

                if (is_null($params[1])) {
                    $params = [];
                }
            }

            $classrooms = $this->classroomService->getClasses($params);
            return ClassroomResponse::collection($classrooms);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/classrooms/{id}",
     *     tags={"Classrooms"},
     *     summary="Get classroom by id",
     *     description="Return a classroom by id",
     *     security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Classroom ID",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Classroom not found"
     *      ),
     *       @OA\Response(
     *            response=500,
     *            description="Internal error"
     *        )
     * )
     */
    public function show($id)
    {
        try {
            $classroom = $this->classroomService->getClassesById($id);

            if ($classroom) {
                return new ClassroomResponse($classroom);
            }

            return response()->json(['message' => 'Class not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/classrooms",
     *     tags={"Classrooms"},
     *     summary="Create a new classroom",
     *     description="Creates a new classroom",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Scalable Systems Architecture"),
 *                  @OA\Property(property="description", type="date", example="Learn how to design and implement robust systems that handle high traffic and scale efficiently."),
     *              @OA\Property(property="type", type="integer", example="1"),
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
    public function store(StoreClassroomRequest $request)
    {
        try {
            $classroom = $this->classroomService->createClassroom($request->validated());
            return new ClassroomResponse($classroom);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/classrooms/{id}",
     *     tags={"Classrooms"},
     *     summary="Update an existing classroom",
     *     description="Updates an existing classroom",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Classroom ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Scalable Systems Architecture"),
     *              @OA\Property(property="description", type="date", example="Learn how to design and implement robust systems that handle high traffic and scale efficiently."),
     *              @OA\Property(property="type", type="integer", example="1"),
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
     *         description="Classroom not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal error"
     *     )
     * )
     */
    public function update(UpdateClassroomRequest $request, $id)
    {
        try {
            $updateClassroom = $this->classroomService->updateClassroom($id, $request->validated());

            if ($updateClassroom) {
                $classroom = $this->classroomService->getClassesById($id);
                return new ClassroomResponse($classroom);
            }
            return response()->json(['message' => 'Classroom not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/classrooms/{id}",
     *     tags={"Classrooms"},
     *     summary="Delete a classroom",
     *     description="Delete a classroom",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Classroom ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Classroom not found"
     *     ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal error"
     *      )
     * )
     */
    public function destroy($id)
    {
        try {
            $delete = $this->classroomService->deleteClassroom($id);
            if ($delete) {
                return response()->json(['message' => 'Classroom deleted successfully'], 200);
            }
            return response()->json(['message' => 'Classroom not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }
}
