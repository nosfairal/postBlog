<?php 
namespace Nosfair\Blogpost\Controller;

use Nosfair\Blogpost\Service\Form;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(\ROOT.'\blogpost\.env');

class ContactController extends Controller
{
    public function index()
    {
        $currentPage = "contact";
       
            if(Form::validate($_POST, ['name', 'firstName', 'email', 'message'])){
                $name = strip_tags($_POST['name']);
                $firstName= strip_tags($_POST['firstName']);
                $email = strip_tags($_POST['email']);
                $message = strip_tags($_POST['message']);
                $mailPass=$_ENV["EPASS"];

                $mail = new PHPMailer(true);
                try{
                    $body="";
                    $body .= "De :".$name." ".$firstName. "\r\n";
                    $body .= "Email: ".$email. "\r\n";
                    $body .= "Message: ".$message. "\r\n";
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com" ;
                    $mail->SMTPAuth = true;
                    $mail->SMTPSecure = 'ssl';
                    $mail->Username = $_ENV["EMAIL"];
                    $mail->Password = $mailPass;
                    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port = 465;   
                    $mail->Charset ="utf-8";
                    $mail->setFrom($email, $name);
                    $mail->addAddress($_ENV["EMAIL"]);
                    $mail->Subject ="Questions via blogPost";
                    $mail->Body = $body;
                    $mail->send();
                    $_SESSION['message'] = "Merci pour votre message, je vous réponds dans les plus brefs délais";
                    \header('Location: ./index.php?p=contact/index');
                    

                }catch(Exception $e){
                    echo "Message non envoyé. Erreur: {$mail->ErrorInfo}";

            };
                

            }else{
                //form doesn't match
                $_SESSION['error'] = !empty($_POST) ? "le formulaire est incomplet" : '';
                $name= isset($_POST['name']) ? strip_tags($_POST['name']) : '';
                $firstName= isset($_POST['firstName']) ? strip_tags($_POST['firstName']) : '';
                $email = isset($_POST['email']) ?strip_tags($_POST['email']): '';
                $message = isset($_POST['message']) ?strip_tags($_POST['message']) : '';
            }
        //instance of Form
        $addContactForm = new Form;
        //Construction of the form
        $addContactForm->startForm()
            ->addLabelFor('name', 'Nom :')
            ->addInput('text', 'name', [
                'class' => 'form-control',
                'value' => $name])
            ->addLabelFor('firstName', 'Prénom :')
            ->addInput('text', 'firstName', [
                'class' => 'form-control',
                'value' => $firstName])
            ->addLabelFor('email', 'Votre mail :')
            ->addInput('text', 'email', [
                'class' => 'form-control',
                'value' => $email])
            ->addLabelFor('message', 'Votre message :')
            ->addTextarea('message', $message, ['class' => 'form-control'])
            ->addInput('hidden', 'recaptcha-response', [
                'id' => 'recaptchaResponse'])
            ->addButton('Valider', ['type' => 'submit', 'class' => 'btn btn-primary'])            ->endForm()
            ;
        $this->twig->display('front/contact.html.twig', ['currentPage' => $currentPage, 'addContactForm' => $addContactForm->create()]);
    }

}