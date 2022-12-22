
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

  

## How to install

  

- In your workspace, clone the project with `git clone {url}`

- Go to project root folder with : `cd api-gateway`

- Run `docker-compose up --build` to build and run the project

- Go to http://localhost:8000/

  

## Why those technical choices

#### After a few researches, I believe Laraval Lumen could be one of the most adapted framework to build this Api Gateway, for those reasons : 
- After my Interview with Carlos, I believe the main back-end framework used at Collective Minds is Laravel. By chosing Lumen which is just Laravel stripped down, I thought that all developers at Collective Minds could easily understand what has been made, and that there would be no learning curve of a new framework.
- I also chose Lumen against Laravel itself because it seems a better fit in terms of microservices development because it's smaller, simpler, leaner, and faster, and seems to be the perfect fit for API development and what I needed to develop here.
  
#### As containerizing the whole solution would make you very happy, I chosed to setup a Dockerfile and a docker-compose.yml myself, following Lumen's requirements (php >= 8.0)

  

+ why those choices (mocking, swagger)

  
 
+ pourquoi ça respecte les demandes


  

● The external system shall be able to create subjects in the Collective Minds system

through the staging layer

  

● The external system shall be able to assign projects to subjects in the Collective Minds

system through the staging layer

  

● Duplicity of subjects inside the same repository is not allowed. Duplicated enrollment of

subjects in projects shall not be allowed either.