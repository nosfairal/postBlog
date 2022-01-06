<?php
require_once 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
$template = new \Twig\Environment($loader, ['cache' => false]);
echo $template->render('index.html.twig', []);