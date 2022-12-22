
  

# Subject Staging Layer - Api Gateway

  

  

## About

  

  

Api gateway to serve only a subset of the Core Api of Collective Minds system

  

- to create subjects in the system

  

- to assign projects to subjects in the system

  

  

## Setup requirements

  

  

- Composer installed on your machine (`composer -v`)

  

- Docker installed on your machine (`docker -v`)

  

- Availability of the docker-compose command (`docker-compose -v`)

  

- Having your machine's 8000 port free for use (`docker ps`)

  

  

## Setup

  

  

- In your workspace, clone the project with `git clone https://github.com/JacquesGarre/api-gateway`

  

- Go to project root folder with : `cd api-gateway`

  

- Run `docker-compose up --build` to build and run the project

  

- Within the container cli, run `composer install`

  

- Copy .env.example to create a .env file and fill CORE_API_BASE_URI, CORE_API_SECRET, ALLOWED_SECRETS constants


- Go to http://localhost:8000/documentation.html if you need to have a look at the api (simplified) documentation


- Within the container cli, you can run tests with `./vendor/bin/phpunit`

  



  

  

## Why those technical choices

  

#### After a few researches, I believe Laraval Lumen could be one of the most adapted framework to build this Api Gateway, for those reasons :

- After my Interview with Carlos, I believe the main back-end framework used at Collective Minds is Laravel. By chosing Lumen which is just Laravel stripped down, I thought that all developers at Collective Minds could easily understand what has been made, and that there would be no learning curve of a new framework.

- I also chose Lumen against Laravel itself because it seems a better fit in terms of microservices development because it's smaller, simpler, leaner, and faster, and seems to be the perfect fit for API development and what I needed to develop here.

#### As containerizing the whole solution would make you very happy, I chosed to setup a Dockerfile and a docker-compose.yml myself, following Lumen's requirements (php >= 8.0)

#### I chosed swagger to generate a quick documentation of both endpoints I implemented

 #### I chosed Http::fake() to mock CoreAPI responses, so I could run some unit tests (available in the tests/ folder) and executable with `./vendor/bin/phpunit` in the container cli.


  ## Assumptions
  
 Since I didn't know what the CoreAPI could return, or what would be needed as parameters, and for the matter of making easier the challenge, I assumed : 
  - GET /repositories/{repositoryID}/projects endpoint would return an array of projectID within the given repositoryID
  - GET /repositories/{repositoryID}/subjects endpoint would return an array of subjectID within the given repositoryID
  - GET /repositories/{repositoryID}/subjects/{subjectID}/projects would return an array of projectID assigned to the given subject, within the gin repository
  - POST /repositories/{repositoryID}/subjects/{subjectID} endpoint would create a subject in the given repository (I also only sent the subjectID in the body, and prepared an additional "data" attribute in case a subject would be more than just an ID)
  - POST /repositories/{repositoryID}/subjects/{subjectID}/projects/{projectID} would assign the project the to the given subjectID within the given repository

Concerning Core API authentication, I assumed Core API would allow only certain apps to request that Api, and I use a bearer token, straight declared in the .env file (CORE_API_BEARER=b34R3Rt0k3n). It wouldn't be long to implement a proper authentication method though.

Concerning SubjectStagingLayer / Api Gateway authentication, I also declared secret keys in .env, that are checked within the middleware AuthenticationAccess.



  

## What has been done

- Middleware (app\Http\Middleware\AuthenticateAccess.php) to force all requests to use a Authorization header, with one of the values defined in .env ALLOWED_SECRETS constant
-  Trait (app\Traits\ApiResponse.php) to manage more easily api requests responses
-  Trait (app\Traits\RequestService.php) to not repeat myself in the CoreApiService (app\Services\CoreApiService.php)
- Service (app\Services\CoreApiService.php) to handle CoreAPI requests
- ApiController that contains two methods (storeSubject() and assignProjectToSubject()), to serve POST /api/subject and POST /api/subject/{subjectID}/project routes
- Unit tests, to test ApiController methods and CoreApiService methods
- Mocked responses of the CoreApi within the TestCase.php file
- Generation of a basic documentation with swagger (http://localhost:8000/documentation.html)


  
## Final words


I would like to thank you for letting me the opportunity to try this very challenging exercise. I enjoyed doing it, even if it has probably been the longest I've ever did for technical interviews (+8 hours spent). I have learnt a lot (1st time using Lumen / Swagger) and it has been a very interesting experience!


I'm looking forward to hear your feedback on my work,

Cheers, 
Jacques Garré