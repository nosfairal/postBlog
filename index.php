<?php
require_once 'vendor/autoload.php';
use Nosfair\Blogpost\Entity\User;
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$template = new \Twig\Environment($loader, ['cache' => false]);
echo $template->render('index.html.twig', []);
$user1 = new User([
    'userId' => 1,
    'lastName' => 'Bon',
    'firstName' => 'Jean',
    'publicName' =>'Jambon',
    'emailAddress' => 'jambon@gmail.com',
    'password' => 'toto',
    'userStatus' => 'approuvé',
    'userRole' => 'admin',
    'creationDate' => '2022/01/07']);
var_dump($user1);