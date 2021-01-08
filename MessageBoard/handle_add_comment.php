<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  
  $content = $_POST['content'];
  if (empty($_POST['content'])) {
    header("location:index.php?errCode=1");
    die();
  }

  $username = $_SESSION['username'];
  $sql = "INSERT INTO bryan9411_comments(content, username) Values(?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ss',$content, $username);
  $result = $stmt->execute();
  header("location:index.php");
?>