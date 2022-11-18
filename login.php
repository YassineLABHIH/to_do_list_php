<?php
// Demarage de la session
session_start();

// Ouverture du canal de discussion (connexion à la base de donnée)
  try {
    require_once 'pdo.php';

// Verification de la soumission du formulaire
    if (isset($_POST['submit'])) {


// Verification que tous les champs ont été remplies
      if (empty($_POST['login_email']) || empty($_POST['login_password'])) {
        $message = "Veuillez remplir tous les champs !";
      } else {
        $login_email = htmlspecialchars($_POST['login_email']);
        $login_password = sha1($_POST['login_password']);

        if (filter_var($login_email, FILTER_VALIDATE_EMAIL)) {

        $recup_user = $pdo->prepare('SELECT * FROM user WHERE email = ? AND pass = ?');
        $recup_user->execute(array($login_email, $login_password));

// Verification que l'email et le mot de passe saisie se trouve dans la table user de la base donnée
      $count = $recup_user->rowCount();
      if ($count > 0) {

// Connexion         
        $_SESSION['conected'] = true;
        $_SESSION['login_email'] = $login_email;
        $_SESSION['id'] = $recup_user->fetch()['id'];

        header("Location: index.php");
      } else {
        $message = "Les informations saisies sont incorrectes !";
      }
    } else {
      $message = "Veuillez saisir une adresse mail valide";
    }
    }
  }
// Si erreur de connexion au serveur renvoi un message d'erreur

  } catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "<br/>";
    die();
  }
// Affichage du view
  require_once './view/login_view.php';

  unset($pdo);


