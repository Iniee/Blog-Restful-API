# Laravel Blog API

## Overview

This project is a RESTful API built with Laravel for managing blogs, posts, likes, and comments. It features user authentication and provides endpoints for creating, updating, deleting, and interacting with blog posts.

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL or MariaDB
- Laravel 11

## Installation

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/yourusername/blog-api-project.git
cd blog-api-project

composer install

cp .env.example .env
php artisan key:generate

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

php artisan migrate

php artisan db:seed

php artisan serve


