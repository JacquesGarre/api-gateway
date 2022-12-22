<?php
 
namespace Tests;

use Tests\TestCase;
 
use App\Services\CoreApiService;

class CoreApiServiceTest extends TestCase
{
    
    /**
     * Testing createSubject method
     *
     * @return void
     */
    public function testCreateSubjectMethod()
    {
        $this->mockCoreApiResponses();
        $coreApiService = new CoreApiService();

        $subjectID = 1;
        $repositoryID = 1;
        $data = [];
        $response = $coreApiService->createSubject($repositoryID, $subjectID);
        $this->assertEquals($response, '{"message":"Subject created"}');
    }

    /**
     * Testing fetchSubjectIdsByRepository method
     *
     * @return void
     */
    public function testFetchSubjectIdsByRepositoryMethod()
    {
        $this->mockCoreApiResponses();
        $coreApiService = new CoreApiService();

        $repositoryID = 1;
        $response = $coreApiService->fetchSubjectIdsByRepository($repositoryID);
        $this->assertEquals($response, '[]');

        $repositoryID = 2;
        $response = $coreApiService->fetchSubjectIdsByRepository($repositoryID);
        $this->assertEquals($response, '[1,2,3,4,5,6,7]');
    }

    /**
     * Testing fetchProjectsByRepositoryId method
     *
     * @return void
     */
    public function testFetchProjectsByRepositoryIdMethod()
    {
        $this->mockCoreApiResponses();
        $coreApiService = new CoreApiService();

        $repositoryID = 1;
        $response = $coreApiService->fetchProjectsByRepositoryId($repositoryID);
        $this->assertEquals($response, '[]');

        $repositoryID = 2;
        $response = $coreApiService->fetchProjectsByRepositoryId($repositoryID);
        $this->assertEquals($response, '[1,2,3,4,5]');
    }

    /**
     * Testing fetchProjectsBySubjectIdAndRepositoryId method
     *
     * @return void
     */
    public function testFetchProjectsBySubjectIdAndRepositoryIdMethod()
    {
        $this->mockCoreApiResponses();
        $coreApiService = new CoreApiService();

        $subjectID = 1;
        $repositoryID = 1;
        $response = $coreApiService->fetchProjectsBySubjectIdAndRepositoryId($subjectID, $repositoryID);
        $this->assertEquals($response, '[]');

        $subjectID = 1;
        $repositoryID = 3;
        $response = $coreApiService->fetchProjectsBySubjectIdAndRepositoryId($subjectID, $repositoryID);
        $this->assertEquals($response, '[1,2,3,4]');
    }

    /**
     * Testing assignProjectToSubject method
     *
     * @return void
     */
    public function testAssignProjectToSubjectMethod()
    {
        $this->mockCoreApiResponses();
        $coreApiService = new CoreApiService();

        $subjectID = 3;
        $repositoryID = 3;
        $projectID = 3;
        $response = $coreApiService->assignProjectToSubject($repositoryID, $subjectID, $projectID);
        $this->assertEquals($response, '{"message":"Project assigned to subject"}');

    }


}