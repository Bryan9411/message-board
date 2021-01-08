<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  $nickname = $_POST['nickname'];
  $username = $_SESSION['username'];
  $sql = "UPDATE bryan9411_users set nickname=? WHERE username=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $nickname, $username);
  $result = $stmt->execute();
  if(!$result) {
    die($conn->error);
  }
  header('location:index.php');
?>