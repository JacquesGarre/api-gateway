<?php
 
namespace Tests;

use Tests\TestCase;
use Illuminate\Http\Response;

class ApiGatewayTest extends TestCase
{

    /**
     * Testing store subject endpoint (POST /api/subject) without Authorization Header 
     *
     * @return void
     */
    public function testStoreSubjectEndpointWithoutAuthorizationHeader()
    {
        $this->mockCoreApiResponses();
        $response = $this->call('POST', '/api/subject', [
            'subjectID' => 1,
            'repositoryID' => 1
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->status());
    }


    /**
     * Testing store subject endpoint (POST /api/subject)
     * Creating a new subject in a repository with no subjects
     *
     * @return void
     */
    public function testStoreSubjectEndpointInEmptyRepo()
    {
        $this->mockCoreApiResponses();
        $response = $this->call(
            'POST', '/api/subject', 
            [
                'subjectID' => 1,
                'repositoryID' => 1
            ],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
        $this->assertEquals('{"message":"Subject created"}', $response->getContent());
    }

    /**
     * Testing store subject endpoint (POST /api/subject)
     * Creating a new subject in a repository with existing subjects
     *
     * @return void
     */
    public function testStoreSubjectEndpointInRepo()
    {
        $this->mockCoreApiResponses();
        $response = $this->call(
            'POST', 
            '/api/subject', 
            [
                'subjectID' => 8,
                'repositoryID' => 2
            ],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_CREATED, $response->status());
        $this->assertEquals('{"message":"Subject created"}', $response->getContent());
    }

    /**
     * Testing store subject endpoint (POST /api/subject)
     * Creating a new subject in a repository where subjectID already exists
     *
     * @return void
     */
    public function testStoreSubjectEndpointInRepoWhereItExists()
    {
        $this->mockCoreApiResponses();
        $response = $this->call('POST', '/api/subject', [
                'subjectID' => 1,
                'repositoryID' => 2
            ],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertEquals('{"error":"This subject already exists in this repository","error_code":400}', $response->getContent());
    }

    /**
     * Testing store subject endpoint (POST /api/subject) with missing params
     * 
     * @return void
     */
    public function testStoreSubjectEndpointWithoutParams()
    {
        $this->mockCoreApiResponses();
        $response = $this->call(
            'POST', 
            '/api/subject', 
            [],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertEquals('{"error":"Missing parameters","error_code":400}', $response->getContent());
    }


    /**
     * Testing to assign a project endpoint (POST /api/subject/{subjectID}/project) without Authorization Header 
     *
     * @return void
     */
    public function testassignProjectEndpointWithoutAuthorizationHeader()
    {
        $this->mockCoreApiResponses();
        $response = $this->call('POST', '/api/subject/1/project', [
            'projectID' => 1,
            'repositoryID' => 1
        ]);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->status());
    }

    /**
     * Testing to assign a project endpoint (POST /api/subject/{subjectID}/project) without data
     *
     * @return void
     */
    public function testAssignProjectEndpointWithoutParams()
    {
        $this->mockCoreApiResponses();

        $response = $this->call(
            'POST', 
            '/api/subject/1/project', 
            [],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertEquals('{"error":"Missing parameters","error_code":400}', $response->getContent());
    }

    /**
     * Testing to assign a project that doesn't exist to a subject (POST /api/subject/{subjectID}/project)
     *
     * @return void
     */
    public function testAssignUnexistingProject()
    {
        $this->mockCoreApiResponses();

        $response = $this->call(
            'POST', 
            '/api/subject/1/project', 
            [
                'projectID' => 1,
                'repositoryID' => 1
            ],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertEquals('{"error":"This project doesn\'t exist in this repository","error_code":400}', $response->getContent());
    }

    /**
     * Testing to assign a project to a subject that doesn't exist (POST /api/subject/{subjectID}/project)
     *
     * @return void
     */
    public function testAssignProjectToUnexistingSubject()
    {
        $this->mockCoreApiResponses();

        $response = $this->call(
            'POST', 
            '/api/subject/15/project', 
            [
                'projectID' => 2,
                'repositoryID' => 2
            ],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertEquals('{"error":"This subject doesn\'t exist in this repository","error_code":400}', $response->getContent());
    }


    /**
     * Testing to assign a project to a subject when it's already assigned (POST /api/subject/{subjectID}/project)
     *
     * @return void
     */
    public function testAssignProjectToSubjectAlreadyAssigned()
    {
        $this->mockCoreApiResponses();

        $response = $this->call(
            'POST', 
            '/api/subject/10/project', 
            [
                'projectID' => 21,
                'repositoryID' => 10
            ],
            [],
            [], 
            [
                'HTTP_Authorization' => $this->getSecret()
            ]
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertEquals('{"error":"This subject is already assigned to this project","error_code":400}', $response->getContent());
    }


}