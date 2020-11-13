# Bilemo

### Project #7 - Application Developer Student - PHP / Symfony Openclassrooms

Create a web service exposing an API with Symfony 5.

## Application installation

### Minimum required

* Apache server 2.4 ou supérieur.  
* Version PHP 7 ou supérieure. 

### Installation

* Clone the project on Github https://github.com/Vincent-gv/bilemo and add it to the projects folder of your local server environment with the command:
`` 
git clone https://github.com/Vincent-gv/bilemo.git
`` 
* Run 
`` composer install 
``  at the root of the folder to install the dependencies.
* Create a local database and update environment variables in .env file.
* Start your symfony local server with `` symfony server:start
                                       ``  
* Run Doctrine to load SQL tables: 
`` 
php bin/console doctrine:database:create
`` 
 * Load fixtures into the database: 
`` 
 php bin/console doctrine:fixtures:load
`` 

### Run application
* Open Postman and get token at localhost/token    
* With the POST method enter your logs in the body in json format:
`` 
{
	"username": "user1@user.com",
	"password": "azerty"
}
`` 
* Get the JWT token and paste it in Authorization > Bearer Token to navigate in the API.

* The documentation is available at the root of the project with public access.

* You can test online with credentials above: https://bilemo.vincent-dev.com/


## Developed with

* ** Symfony 5.1 **
* ** PHP 7.4.7 **
* ** Mysql **
* **Composer **

## Author

** Vincent Gauchevertu ** - Openclassrooms student
https://github.com/Vincent-gv/

## Project badges

<a href="https://codeclimate.com/github/Vincent-gv/bilemo/maintainability"><img src="https://api.codeclimate.com/v1/badges/5e46623191ffd04e55ba/maintainability" /></a>

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/9d83abbf3467409a95546a836e0777e5)](https://www.codacy.com/gh/Vincent-gv/bilemo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Vincent-gv/bilemo&amp;utm_campaign=Badge_Grade)
