Gusto

Note: api.gusto.local domain is my local domain. Please configure your local domain.

1. Application has been created using Symfony framework version 3.4 (https://symfony.com/).
2. To run local server use: php bin/console server:start, as escribed on https://symfony.com/doc/3.4/setup.html#running-the-symfony-application
3. API usage:

- [GET] /recipe/recipeId, e.g. http://api.gusto.local/recipe/1
- [GET] /recipe/cuisine/recipeCusin, e.g. http://api.gusto.local/recipe/cuisine/asian
- [PATCH] /recipe/recipeId, e.g. http://api.gusto.local/recipe/1
  Request body:
  {
  "title": "New title 1"
  }

4. Ryn tests (tests/AppBundle/Controller):

- ./vendor/bin/simple-phpunit

4. Code structure: all API endpoints are loocated in DefaultController.php (https://github.com/strabek/gusto/blob/master/src/AppBundle/Controller/DefaultController.php)
5. List of missing functional requirements:

- paging - not enough time

Possible improvements/functionality:

1. Use of database
2. Frint-end UI

Anything else you think is relevant to your solution

1. The application was build on Ubuntu 18, PHP 7.3
2. Datafile is in web folder to simplify access

I have spent around 2.5h on the test, iincluding setting up the project.
