<p align="center"><a href="https://riseuplabs.com/" target="_blank"><img src="https://cdn-bcikh.nitrocdn.com/VCMuauOnKdIuGHOdXXWcAEouHFNRgAdk/assets/static/optimized/rev-3db7904/wp-content/uploads/2021/09/logo.png" width="200"></a></p>


## Assignment (PHP Laravel) 

## Objective: 
-Let say we are going to create a backend service (Rest APIs) for a job portal. The
application will have two types of users. Admin and the general user. Admin can create, view,
single view, update and delete job posts. General users can view job posts after login with valid
credentials.

## Language and Framework:
	- **Laravel 8 or Lumen 8**

## Project Type: 
	- **Backend Service (REST APIs)**

## Public Access: 
	- **Registration (General users)**
	- **Login.**

## General User Access: 
	- **View the job post as the list and single, Logout.**

## Admin User Access: 
	- **Create, list view, single view, update and delete job posts, Create Admin
	User, Logout.**

## User Schema:
    - **name**
	- **email**
	- **password**
	- **role**

## Post Schema:
	- **title**
	- **description**
	- **users_id**
	- **thumbnail**
	- **status**

## Installation Guideline 
- Create a database locally named `homestead` utf8_general_ci 
- Download composer https://getcomposer.org/download/
- Clone `git clone https://github.com/gmfaruk/RiseUpLab-Test.git`.
- Rename `.env.example` file to `.env`inside RiseUpLab-Test root and fill the database information.
- Open the console and cd RiseUpLab-Test root directory
- Run `composer install` or ```php composer.phar install```
- Run `php artisan key:generate` 
- Run `php artisan migrate`
- Run `php artisan passport:client --personal` to run passport client.Name `RiseUpLab`.
- Run `php artisan serve`

## API docs on Postman:
- `Import collection file from root: RiseUpLab.postman_collection.json`
- `Register a user as a admin.`
- `Login Using email & Password.`
- `After Login copy token and using this token on (Logout, All Post, Post Store, Show Single Post, Update Post, Delete Post) Postman Authorization > Type : Bearer Token.`

## If for some reason your project stop working do these:
- `composer install`
- `php artisan cache:clear`
- `php artisan config:clear`
- `php artisan view:clear`
- `php artisan key:generate`
- `composer dump-autoload -o`
- `php artisan serve`
