<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));
    $honeypot = trim($_POST['honeypot']);

    // Vérification Honeypot (anti-bot)
    if (!empty($honeypot)) {
        die("Spam détecté !");
    }

    // Vérification Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Adresse email invalide.");
    }

    // Vérification du CAPTCHA Google
    $recaptcha_secret = "VOTRE_SECRET_KEY";
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
        die("CAPTCHA invalide. Veuillez réessayer.");
    }

    // Envoi du mail
    $to = "votre.email@example.com";
    $subject = "Nouveau message de Bar Smash";
    $headers = "From: $email\r\nReply-To: $email\r\nContent-Type: text/plain; charset=UTF-8";
    $body = "Nom: $name\nEmail: $email\n\nMessage:\n$message";

    if (mail($to, $subject, $body, $headers)) {
        echo "Message envoyé avec succès !";
    } else {
        echo "Erreur lors de l'envoi du message.";
    }
} else {
    echo "Méthode invalide.";
}
?>
