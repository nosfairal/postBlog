<?php 
namespace Nosfair\Blogpost\Controller;

use Nosfair\Blogpost\Service\Form;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Nosfair\Blogpost\Service\Session;
use PHPMailer\PHPMailer\SMTP;
use Nosfair\Blogpost\Service\GlobalConstant;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(\ROOT.'\blogpost\.env');

class ContactController extends Controller
{
    public function index()
    {
        $currentPage = "contact";
        //Instance of Session
        $session = new Session;
        $sessionStop = $session->forget('error');
        $sessionStopMessage = $session->forget('message');
        //instance of Globalconstant
        $global = new GlobalConstant;
       
        if(Form::validate($_POST, ['name', 'firstName', 'email', 'message'])){
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
            $emailSafe = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $email = filter_var($emailSafe,FILTER_VALIDATE_EMAIL); 
            $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING); 
            $mailPass= filter_var($global->Env("EPASS") , FILTER_DEFAULT );
            $myMail = filter_var($global->Env("EMAIL") , FILTER_DEFAULT );
            $host= filter_var($global->Env("HOST") , FILTER_DEFAULT );
            $port = filter_var($global->Env("PORT") , FILTER_DEFAULT );

            $mail = new PHPMailer(true);
            try{
                $body="";
                $body .= "De :".$name." ".$firstName. "\r\n";
                $body .= "Email: ".$email. "\r\n";
                $body .= "Message: ".$message. "\r\n";
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host = $host;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Username = $myMail;
                $mail->Password = $mailPass;
                //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port = $port;   
                $mail->Charset ="utf-8";
                $mail->setFrom($email, $name);
                $mail->addAddress($myMail);
                $mail->Subject ="Questions via blogPost";
                $mail->Body = $body;
                $mail->send();
                $session->put("message","Merci pour votre message, je vous réponds dans les plus brefs délais");
                $session->redirect("./index.php?p=contact/index");
                

            }catch(Exception $e){
                $session->put("message","Votre message n'a pas pu être envoyé");  
        };
            

        }else{
            //form doesn't match
            $session->put("error","le formulaire de contact est incomplet");
            $name= $global->issetPost('name') ? filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
            $firstName= $global->issetPost('firstName') ? filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS) : '';
            $email = $global->issetPost('email') ? filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL): '';
            $message = $global->issetPost('message') ? filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING) : '';
        }
        //instance of Form
        $addContactForm = new Form;
        //Construction of the form
        $addContactForm->startForm()
            ->addLabelFor('name', 'Nom :')
            ->addInput('text', 'name', [
                'class' => 'form-control mb-2',
                'value' => $name])
            ->addLabelFor('firstName', 'Prénom :')
            ->addInput('text', 'firstName', [
                'class' => 'form-control mb-2',
                'value' => $firstName])
            ->addLabelFor('email', 'Votre mail :')
            ->addInput('text', 'email', [
                'class' => 'form-control mb-2',
                'value' => $email])
            ->addLabelFor('message', 'Votre message :')
            ->addTextarea('message', $message, ['class' => 'form-control mb-2'])
            ->addInput('hidden', 'recaptcha-response', [
                'id' => 'recaptchaResponse'])
            ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])
            ->endForm()
            ;
        $this->twig->display('front/contact.html.twig', ['sessionStopMessage' => $sessionStopMessage, 'sessionStop' => $sessionStop, 'currentPage' => $currentPage, 'addContactForm' => $addContactForm->create()]);
    }

}