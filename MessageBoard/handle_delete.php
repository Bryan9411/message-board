<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  $id = $_GET['id'];
  
  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $sql = "UPDATE bryan9411_comments set is_deleted=1 WHERE id=? and username=?";
  if (isAdmin($user)) {
    $sql = "UPDATE bryan9411_comments set is_deleted=1 WHERE id=?";
  }
  $stmt = $conn->prepare($sql);
  if (isAdmin($user)) {
    $stmt->bind_param('i', $id);
  } else {
    $stmt->bind_param('is', $id, $username);
  }
  $result = $stmt->execute();
  header("location:index.php");
?>