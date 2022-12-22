<?php

namespace App\Http\Controllers;

use App\Services\CoreApiService;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    use ApiResponse;

    private $coreApiService;

    /**
     * ApiController constructor.
     *
     * @param \App\Services\CoreApiService $coreApiService
     */
    public function __construct(CoreApiService $coreApiService)
    {
        $this->coreApiService = $coreApiService;
    }

    /**
     * @OA\Post(
     *     path="/api/subject",
     *     operationId="/api/subject",
     *     tags={"create_subject"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="subjectID",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="repositoryID",
     *                     type="int"
     *                 ),
     *                 example={"subjectID": 1, "repositoryID": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description=""
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function storeSubject(Request $request)
    {
        $repositoryID = $request->get('repositoryID');
        $subjectID = $request->get('subjectID');
        $data = $request->get('data');

        // Check mandatory params to create a subject
        if (null == $repositoryID || null == $subjectID) {
            return $this->errorResponse("Missing parameters", Response::HTTP_BAD_REQUEST);
        }

        // Check for duplicates (Duplicity of subjects inside the same repository is not allowed.)
        $response = $this->successResponse($this->coreApiService->fetchSubjectIdsByRepository($repositoryID));
        $existingSubjectIDS = json_decode($response->getContent(), true);
        if (in_array($subjectID, $existingSubjectIDS)) {
            return $this->errorResponse("This subject already exists in this repository", Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(
            $this->coreApiService->createSubject($repositoryID, $subjectID, $data),
            Response::HTTP_CREATED
        );
    }


    /**
     * @OA\Post(
     *     path="/api/subject/{subjectID}/project",
     *     operationId="/api/subject/subjectID/project",
     *     tags={"assign_project_to_subject"},
     *     @OA\Parameter(
     *         name="subjectID",
     *         in="path",
     *         description="The subjectID that you want to assign to a project in a repository",
     *         required=true,
     *         @OA\Schema(type="int")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="projectID",
     *                     type="int"
     *                 ),
     *                 @OA\Property(
     *                     property="repositoryID",
     *                     type="int"
     *                 ),
     *                 example={"projectID": 1, "repositoryID": 1}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description=""
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Error: Bad request. When required parameters were not supplied.",
     *     ),
     * )
     */
    public function assignProjectToSubject(int $subjectID = null, Request $request)
    {
        $repositoryID = $request->get('repositoryID');
        $projectID = $request->get('projectID');

        // Check mandatory params to create a subject
        if (null == $repositoryID || null == $subjectID || null == $projectID) {
            return $this->errorResponse("Missing parameters", Response::HTTP_BAD_REQUEST);
        }

        // Check that project we want to assign to subject exists in this repository
        $response = $this->successResponse($this->coreApiService->fetchProjectsByRepositoryId($repositoryID));
        $existingProjectIDS = json_decode($response->getContent(), true);

        if (!in_array($projectID, $existingProjectIDS)) {
            return $this->errorResponse("This project doesn't exist in this repository", Response::HTTP_BAD_REQUEST);
        }

        // Check that subject we want to assign to project exists in this repository
        $response = $this->successResponse($this->coreApiService->fetchSubjectIdsByRepository($repositoryID));
        $existingSubjectIDS = json_decode($response->getContent(), true);
        if (!in_array($subjectID, $existingSubjectIDS)) {
            return $this->errorResponse("This subject doesn't exist in this repository", Response::HTTP_BAD_REQUEST);
        }

        // Check for duplicates (Duplicated enrollment of subjects in projects shall not be allowed)
        $response = $this->successResponse($this->coreApiService->fetchProjectsBySubjectIdAndRepositoryId($subjectID, $repositoryID));
        $subjectProjectIDS = json_decode($response->getContent(), true);
        if (in_array($projectID, $subjectProjectIDS)) {
            return $this->errorResponse("This subject is already assigned to this project", Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse(
            $this->coreApiService->assignProjectToSubject($repositoryID, $subjectID, $projectID),
            Response::HTTP_CREATED
        );
    }
}
