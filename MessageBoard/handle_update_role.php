<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  if (empty($_POST['role'])) {
    header("location:backstage.php?errCode=1");
    die('資料不齊全');
  }
  $role = $_POST['role'];
  $username = $_POST['username'];
  $sql = "UPDATE bryan9411_users set role=? WHERE username=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss', $role, $username);
  $result = $stmt->execute();
  if (!$result) {
    die($conn->error);
  }
  header("location:backstage.php");
?>