# PostBlog
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/aa0d47c4b90042889836b0428ede6c80)](https://www.codacy.com/gh/nosfairal/postBlog/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=nosfairal/postBlog&amp;utm_campaign=Badge_Grade)

Professionnal PHP blog, without CMS neither PHP framework.

This is the fifth project of the formation Application Developer - PHP / Symfony on Openclassrooms.

## Table of contents
1.  Prerequisites and technologies
    -   Server
    -   Languages and libraries

2.  Installation

## Prerequisites and technologies

Server:

You need a web server with PHP7 and MySQL DBMS.

Versions used in this project:

    Apache 2.4.46
    PHP 7.4.14
    MariaDB 10.4.14

You also need an access to a SMTP server.

Languages and libraries:

This project is coded in PHP7, HTML5, CSS3 and JS.

Dependencies manager: Composer

PHP packages, included via Composer:

    . twig/twig: ^3.3,
    . twbs/bootstrap: 5.1.3,
    . symfony/dotenv: ^5.4,
    . phpmailer/phpmailer: ^6.5,
    . components/jquery: ^3.6,
    . components/jqueryui: ^1.12

## Installation

Download zip files or clone the project repository with github (see GitHub documentation).

1.  You have to create an .env file.

2.  Replace the example values below with your own values.

### .env
    . DBHOST=db-host_name
    . DBUSER=db-username
    . DBPASS= db-password
    . DBNAME=db_name
    . EMAIL=your@email.com
    . EPASS=your email password
    . HOST=SMTP host_name
    . PORT=your port

3.  Create a new MySQl Datbase in your DBMS with the db_name chosen above.
   
4.  Import the Sql file : https://github.com/nosfairal/postBlog/blob/master/postblog.sql .

5.  Install composer by following this instructions : https://getcomposer.org/download/.

6.  Install dependencies with the following command :
    `` $ composer install ``

7.  Open index.php file in your favorite browser. This is the home page.

8.  Register you as a new user via registration form (index.php?p=user/register).

9.  Update your user profile in the database to set status to approved and role to admin.