<?php
// Demarage de la session
  session_start();

// Vérification que le client est bien identifié
  if($_SESSION['conected'] == true){


// START : ouverture du canal de discussion  
  try {
 require_once 'pdo.php';
// END : ouverture du canal de discussion

  $today = date("Y-m-d");

// START : Effacer des taches 
   if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete']) && isset($_POST['selectedTask'])) {
   $delete = $pdo->prepare('DELETE FROM task WHERE id= :id');
   foreach ($_POST['selectedTask'] as $id){
      $delete->execute(['id'=>$id]);
    }
  }
// END : Effacer des taches

// START : Declarer une tache comme étant déja fait
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['is_done']) && isset($_POST['selectedTask'])) {
   $is_done = $pdo->prepare('UPDATE task SET is_done = 1 WHERE id= :id');
   foreach ($_POST['selectedTask'] as $id){
      $is_done->execute(['id'=>$id]);
    }
  }
// END : Declarer une tache comme étant déja fait


// START : trier par ordre croissant ou décroissant
  $order_request = null;
  if(isset($_GET['order']) && in_array($_GET['order'], ['ASC', 'DESC']))
  {
    $order_request = 'ORDER BY to_do_at '.$_GET['order'];
    $_SESSION['order'] = $order_request;
   
  }

  $recup_task = $pdo->prepare("SELECT * FROM task WHERE id_user = ? ".isset($_SESSION['order'])."");
  $recup_task->execute(array($_SESSION['id']));
  $tasks = $recup_task->fetchAll(PDO::FETCH_ASSOC);
// END : trier par ordre croissant ou décroissant

// START : Recuperation du nom de l'utilisateur
  $recup_name = $pdo->prepare('SELECT * FROM user WHERE id= ? ');
  $recup_name->execute(array($_SESSION['id']));
  $name = $recup_name->fetch(PDO::FETCH_ASSOC);
// END : Recuperation du nom de l'utilisateur

// Si erreur de connexion au serveur renvoi un message d'erreur
  } catch (PDOException $e) {
     echo "Erreur: " . $e->getMessage() . "<br/>";
      die();
   }

// Si le client n'est pas indentifié redirection vers la page login
  }else{

      header("Location: login.php");
  }

// Affichage du view
  require_once './view/index_view.php';

unset($pdo);

