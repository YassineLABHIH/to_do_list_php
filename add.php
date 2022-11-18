<?php
// Demarage de la session
  session_start();
// Vérification que le client est bien identifié
if($_SESSION['conected'] == true){

// START : ouverture du canal de discussion
  try {
    require_once 'pdo.php';;
// END : ouverture du canal de discussion

// START : Ajout de la nouvelle tache

      if ($_SERVER['REQUEST_METHOD'] == 'POST'){

      $to_do_at = $_POST['year']."-".$_POST['month']."-".$_POST['day'];

      $insert = $pdo->prepare('INSERT INTO task (name, to_do_at, is_done, id_user) VALUES (:name, :to_do_at, :is_done, :id_user)');
      $insert->execute([
        'name'=>htmlspecialchars($_POST['name']),
        'to_do_at'=>htmlspecialchars($_POST['to_do_at']),
        'is_done'=>false,
        'id_user'=>$_SESSION['id']
      ]);
// END : Ajout de la nouvelle tache

// Redirection vers page index
      header("Location: index.php");
  
      }

// Si erreur de connexion au serveur renvoi un message d'erreur
  }catch (PDOException $e) {
     echo "Erreur: " . $e->getMessage() . "<br/>";
     die();
   }
  }else{

    header("Location: login.php");
}

// Affichage du view
  require_once './view/add_view.php';

  unset($pdo);



