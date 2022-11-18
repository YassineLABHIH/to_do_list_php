<?php
// Demarage de la session
  session_start();
// Vérification que le client est bien identifié
if($_SESSION['conected'] == true){
  
// START : Recuperation de l'id de la tache à modifier
  $edit_id = $_GET['id'];
// END : Recuperation de l'id de la tache à modifier

// START : ouverture du canal de discussion
  try { 
    require_once 'pdo.php';
// END : ouverture du canal de discussion

// START : Modification de la tache

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $edit = $pdo->prepare("UPDATE task SET name = :name, to_do_at = :to_do_at, is_done = :is_done, id_user = :id_user WHERE id = ".$_GET['id']." ");
    $edit->execute(array(
      ':name' => htmlspecialchars($_POST['name']),
      ':to_do_at' => htmlspecialchars($_POST['to_do_at']),
      ':is_done' => htmlspecialchars($_POST['is_done']),
      ':id_user' => $_SESSION['id']
      ));

    header("Location: index.php");
  }
// END : Modification de la tache

// START : Recuperation des données de la tache a modifier
  $stmt = $pdo->prepare('SELECT * FROM task WHERE id = ?');
  $stmt->execute(array($edit_id));
  $task = $stmt->fetch(PDO::FETCH_ASSOC);
// END : Recuperation des données de la tache a modifier

// Si erreur de connexion au serveur renvoi un message d'erreur
  } catch (PDOException $e) {
      echo "Erreur: " . $e->getMessage() . "<br/>";
      die();
    }
  
  }else{

    header("Location: login.php");
}
// Affichage du view
  require_once './view/edit_view.php';

  unset($pdo);



