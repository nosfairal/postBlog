<?php
require_once 'vendor/autoload.php';
use Nosfair\Blogpost\Entity\User;
use Nosfair\Blogpost\Classe\DatabaseConnexion;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$template = new \Twig\Environment($loader, ['cache' => false]);
echo $template->render('index.html.twig', []);
/*$user2 = new User([
    'userId' => 2,
    'lastName' => 'Bon',
    'firstName' => 'Jean',
    'publicName' =>'Jambon',
    'emailAddress' => 'jambon@gmail.com',
    'password' => 'toto',
    'userStatus' => 'approuved',
    'userRole' => 'member',
    'creationDate' => new DateTime('now')]);
var_dump($user2);*/
$db= DatabaseConnexion::dbConnect();

$q = $db->prepare("INSERT INTO user(`userId`, `lastName`, `firstName`, `publicName`, `emailAddress`, `password`, `userStatus`, `userRole`) VALUES (2,'Bon','Jean','Jambon','jambon@totomail.fr','Jambon1!', 'approuved', 'member');");
$q->execute();