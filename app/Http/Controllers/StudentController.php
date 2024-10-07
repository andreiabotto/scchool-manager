<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Responses\Student\StudentResponse;
use App\Services\Interfaces\StudentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *     title="Student manager",
 *     version="1.0.0",
 *     description="Documentation for student manager",
 *     @OA\Contact()
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="sanctum",
 *      type="apiKey",
 *      in="header",
 *      name="Authorization",
 *      description="Autenticação usando Bearer Token. Formato: 'Bearer <token>'"
 *  )
 *
 */
class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentServiceInterface $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * @OA\Get(
     *     path="/api/students",
     *     tags={"Students"},
     *     summary="Get a list of students",
     *     description="Returns a list of students",
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

            $students = $this->studentService->getStudents($params);
            return StudentResponse::collection($students);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Get student by id",
     *     description="Return a student by id",
     *     security={{"sanctum": {}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Student ID",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful response"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Student not found"
     *      ),
     *       @OA\Response(
     *            response=500,
     *            description="Internal error"
     *        )
     * )
     */
    public function show($id)
    {
        try{
            $student = $this->studentService->getStudentById($id);

            if ($student) {
                return new StudentResponse($student);
            }

            return response()->json(['message' => 'Student not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/students",
     *     tags={"Students"},
     *     summary="Create a new student",
     *     description="Creates a new student",
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="John Doe"),
 *                  @OA\Property(property="date_of_birth", type="date", example="2024-12-30"),
     *              @OA\Property(property="user_id", type="integer", example="0")
     *          )
     *     ),
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
    public function store(StoreStudentRequest $request)
    {
        try{
            $student = $this->studentService->createStudent($request->validated());
            return new StudentResponse($student);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Update an existing student",
     *     description="Updates an existing student",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *               @OA\Property(property="name", type="string", example="John Doe"),
     *               @OA\Property(property="date_of_birth", type="date", example="2024-12-30"),
     *               @OA\Property(property="user_id", type="integer", example="0")
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
     *         description="Student not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal error"
     *     )
     * )
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        try{
            $updateStudent = $this->studentService->updateStudent($id, $request->validated());

            if ($updateStudent) {
                $student = $this->studentService->getStudentById($id);
                return new StudentResponse($student);
            }
            return response()->json(['message' => 'Student not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/students/{id}",
     *     tags={"Students"},
     *     summary="Delete a student",
     *     description="Deletes a student",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Student not found"
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
            $deleteStudent = $this->studentService->deleteStudent($id);
            if ($deleteStudent) {
                return response()->json(['message' => 'Student deleted successfully'], 200);
            }
            return response()->json(['message' => 'Student not found'], 404);
        }
        catch (\Exception $e) {
            return response()->json(['code' => $e->getCode(),'message' => $e->getMessage()], 500);
        }
    }
}
