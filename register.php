<?php

// Demarage de la session
  session_start();

// Verification de la soumission du formulaire
  if (isset($_POST['submit'])) {
  
// Verification que tous les champs ont été remplies
  if (empty($_POST['register_email']) || empty($_POST['user_name'] ) || empty($_POST['user_first_name'] ) || empty($_POST['user_password'] ) || empty($_POST['user_confirm_password'] )) {
  $message = "Veuillez remplir tous les champs !";
  } else {

// START : Ouverture du canal de discussion (connexion à la base de donnée)
  try {
    require_once 'pdo.php';
// END : Ouverture du canal de discussion (connexion à la base de donnée)

// Recupération des données du formulaire
  $user_mail = htmlspecialchars($_POST['register_email']);
  $user_name = htmlspecialchars($_POST['user_name']);
  $user_first_name = htmlspecialchars($_POST['user_first_name']);
  $user_password = $_POST['user_password'];
  $user_confirm_password = $_POST['user_confirm_password'];

//vérification de la force du mot de passe
  $point = 0;
  $longueur = strlen($user_password);
 
  for($i = 0; $i < $longueur; $i++) 	{
	  $lettre = isset($user_passwordp[$i]);
	  if ($lettre>='a' && $lettre<='z'){
	  	$point = $point + 1;
	  }
	  if ($lettre>='A' && $lettre <='Z'){
	  	$point = $point + 1;
	  }
	  if ($lettre>='0' && $lettre<='9'){
	  	$point = $point + 1;
	  }
	  else {
	  	$point = $point + 1;
	  }
  }

//vérification que l'adresse mail saisie est valide
  if (filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {

  $recup_user = $pdo->prepare('SELECT * FROM user WHERE email = ?');
  $recup_user->execute(array($user_mail));

// Verification que l'email  saisie ne se trouve dans la table user de la base donnée
  $count = $recup_user->rowCount();
  if ($count > 0) {
    $message = "Vous ne pouvez pas vous inscrire avec ce mail car il est déja inscrit";

// Verification que les mot de passe correspondent
  }elseif($user_password != $user_confirm_password) {
    $message = "Les mots de passe saisies ne corresspondent pas !";
  }elseif($point >= 4 && $longueur >= 8){

// Validation de l'inscription
  $registration = $pdo->prepare("INSERT INTO user (username, user_first_name, email, pass) VALUES (:username, :user_first_name, :email, :pass)");
  $registration->execute(array(
    ':username' => $user_name,
    ':user_first_name' => $user_first_name,
    ':email' => $user_mail,
    ':pass' => sha1($user_password)
  ));

// Redirection vers la page login
    $_SESSION['registration_message'] = "Votre compte a été créer avec succés !";
    header("Location: login.php");
    
}else{
  $message = "Votre mot de passe doit : Faire au moins 8 caractères et contenir au moins une minuscule, une majuscule, un caractère spécial et un chiffre";
}

}else{
  $message = "Veuillez saisir une adresse mail valide";
}

// Si erreur de connexion au serveur renvoi un message d'erreur

  } catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "<br/>";
    die();
  }}}

// Affichage du view
  require_once './view/register_view.php';

  unset($pdo);
