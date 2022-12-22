<?php

namespace Tests;

use Laravel\Lumen\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Mocks Core Api responses
     */
    protected function mockCoreApiResponses()
    {   
        $url = env('CORE_API_BASE_URI').'/API/v1';

        Http::fake([     

            // Let's imagine that only repository with id 2 has subjects with ids [1,2,3,4,5,6,7]
            $url.'/repositories/2/subjects' => Http::response(
                [
                    1,2,3,4,5,6,7
                ], 
                Response::HTTP_OK
            ),

            // When creating a subject with subjectID in repository
            $url.'/repositories/1/subjects/1' => Http::response(
                [
                    "message" => "Subject created"
                ], 
                Response::HTTP_CREATED
            ),

            // When creating a subject with subjectID in repository
            $url.'/repositories/2/subjects/8' => Http::response(
                [
                    "message" => "Subject created"
                ], 
                Response::HTTP_CREATED
            ),


            // When assigning a project to a subject in a repository
            $url.'/repositories/3/subjects/3/projects/3' => Http::response(
                [
                    "message" => "Project assigned to subject"
                ], 
                Response::HTTP_CREATED
            ),

            // When getting projects in repository with projects
            $url.'/repositories/2/projects' => Http::response(
                [1,2,3,4,5], 
                Response::HTTP_OK
            ),

            // Getting projects of a subject that has projects
            $url.'/repositories/3/subjects/1/projects' => Http::response(
                [1,2,3,4], 
                Response::HTTP_OK
            ),
                        
            // Getting projects of a subject that has no projects
            $url.'/repositories/1/subjects/1/projects' => Http::response(
                [], 
                Response::HTTP_OK
            ),

            // Getting projects of a subject that has projects
            $url.'/repositories/10/subjects/10/projects' => Http::response(
                [5,7,8,9,21], 
                Response::HTTP_OK
            ),

            // Getting projects of a repositories that has projects
            $url.'/repositories/10/projects' => Http::response(
                [5,7,8,9,21], 
                Response::HTTP_OK
            ),

            // Getting subjects of a repositories 
            $url.'/repositories/10/subjects' => Http::response(
                [10,11,12], 
                Response::HTTP_OK
            ),

            // When getting projects in a empty repository
            $url.'/repositories/*/projects' => Http::response(
                [], 
                Response::HTTP_OK
            ),

            // All other repositories have no subjects
            $url.'/repositories/*/subjects' => Http::response(
                [], 
                Response::HTTP_OK
            ),



        ]);

    }

    protected function getSecret()
    {
        $allowedSecrets = explode(',', env('ALLOWED_SECRETS'));
        return reset($allowedSecrets);
    }

}
