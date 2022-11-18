<?php
// Demarage de la session
    session_start();
// Deconnexion    
    $_SESSION['conected'] = false;
    unset($_SESSION['login_email']);
    unset($_SESSION['login_password']);
    unset($_SESSION['registration_message']);

// Redirection vers la page login
    header("Location: login.php");