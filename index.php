<?php

use PHPMailer\PHPMailer\PHPMailer;

//Pour utiliser notre objet PHPMailer
use PHPMailer\PHPMailer\Exception;

// Pour utiliser l'objet Exeption

require 'vendor/autoload.php'; //L'autoload permet d'eviter de mettre des require partout

if(!empty($_POST) && $_SERVER['HTTP_HOST'] == 'localhost'){


    $email = htmlspecialchars($_POST['email']);
    $nom = htmlspecialchars($_POST['nom']);
    $objet = htmlspecialchars($_POST['objet']);
    $message = htmlspecialchars(stripslashes(trim($_POST['message'])));

    $erreur = NULL;


    if (!isset($_POST['email']) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur .= '<div> L\'email n\'est pas valide.</div>';
    }

    if (!isset($_POST['nom']) || strlen($nom) < 2) {
        $erreur .= '<div> Le nom doit contenir au minimum 2 caractères.</div>';
    }

    if (!isset($_POST['message']) || strlen($message) < 1) {
        $erreur .= '<div> Le message doit contenir ne doit pas être vide.</div>';
    }


    if (!isset($erreur)) {

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP(); //Pour preciser que c'est du SMTP
            $mail->Host = 'smtp.gmail.com';  // Le serveur smtp de google
            $mail->SMTPAuth = true;                               // On active l'authentification
            $mail->Username = 'antoine.sueur17@gmail.com';                 // SMTP username
            $mail->Password = '***********';                           // Le mot de passe que vous avez récupéré
            $mail->SMTPSecure = 'tls';                            // Parameter de sécurité mis sur TLS
            $mail->Port = 587;                                    // Le port donne par google pour son SMTP

            $mail->setFrom($email, $nom); // De qui est l' email
            $mail->addReplyTo($email, $nom); // Option pour avoir le reply
            $mail->addAddress('antoine.sueur17@gmail.com', 'Antoine Sueur'); //La boite mail où vous voulez recevoir les mails


            $mail->isHTML(true); //Met le mail au format HTML
            $mail->Subject = $objet; // On parametre l'objet
            $mail->Body = $message; // Le message pour les boites html
            $mail->AltBody = $message; //Le message pour les boites non html
            $mail->SMTPDebug = 0; //On désactive les logs de debug

            if ($mail->send()) {
                echo '<div>Mail envoyé ! Merci pour votre interêt.</div>';
            } else {
                $erreur = '<div>Echec dans l\'envoi du mail</div>';
            }
        } catch (Exception $e) {
            $erreur = $e;
        }
    }
}

?>
<?= isset($erreur) ? $erreur : '' ?>
<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
    <label for="nom">Nom : </label><br>
    <input type="text" name="nom" id="nom"><br>

    <label for="email">Email : </label><br>
    <input type="email" name="email" id="email"><br>

    <label for="objet">Objet : </label><br>
    <input type="text" name="objet" id="objet"> <br>

    <label for="message">Message : </label><br>
    <textarea name="message" id="message"></textarea><br>

    <button type="submit" >Envoyer</button>
</form>


