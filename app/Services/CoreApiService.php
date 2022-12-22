<?php

namespace App\Services;

use App\Traits\RequestService;
use function config;

class CoreApiService
{
    use RequestService;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var string
     */
    protected $bearer;

    public function __construct()
    {
        $this->baseUri = config('services.core_api.base_uri');
        $this->bearer = config('services.core_api.bearer');
    }

    /**
     * @param $repositoryID
     * @param $subjectID
     * @param $data
     *
     * @return string
     */
    public function createSubject($repositoryID, $subjectID, $data = []) : string
    {   
        return $this->request('post', "/API/v1/repositories/{$repositoryID}/subjects/{$subjectID}", $data);
    }

    /**
     * @param $repositoryID
     *
     * @return string
     */
    public function fetchSubjectIdsByRepository($repositoryID) : string
    {   
        return $this->request('get', "/API/v1/repositories/{$repositoryID}/subjects");
    }

    /**
     * @param $repositoryID
     *
     * @return string
     */
    public function fetchProjectsByRepositoryId($repositoryID) : string
    {   
        return $this->request('get', "/API/v1/repositories/{$repositoryID}/projects");
    }

    /**
     * @param $subjectID
     * @param $repositoryID
     *
     * @return string
     */
    public function fetchProjectsBySubjectIdAndRepositoryId($subjectID, $repositoryID) : string
    {       
        return $this->request('get', "/API/v1/repositories/{$repositoryID}/subjects/{$subjectID}/projects");
    }

    /**
     * @param $subjectID
     * @param $repositoryID
     *
     * @return string
     */
    public function assignProjectToSubject($repositoryID, $subjectID, $projectID) : string
    {
        return $this->request('post', "/API/v1/repositories/{$repositoryID}/subjects/{$subjectID}/projects/{$projectID}");
    }

}
