<?php
ini_set("sendmail_from", "yourirenoudgrappin@gmail.com");

$to = "yourirenoudgrappe@gmail.com";
$subject = "Test Mail XAMPP";
$message = "Ceci est un test d'envoi via XAMPP Sendmail (Gmail).";
$headers = "From: yourirenoudgrappin@gmail.com";

if (mail($to, $subject, $message, $headers)) {
    echo "✅ Mail envoyé (vérifie ta boîte Gmail).";
} else {
    echo "❌ Échec de l'envoi.";
}
?>
