<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');

  $username = $_SESSION['username'];
  $user = getUserFromUsername($username);
  $content = escape($_POST['content']);
  $id = $_POST['id'];
  if (empty($_POST['content'])) {
    header("location:update_comment.php?ierrCode=1&id=" . $_POST['id']);
    die();
  }
  $sql = "UPDATE bryan9411_comments SET content=? WHERE id=? and username=?";
  if(isAdmin($user)) {
    $sql = "UPDATE bryan9411_comments SET content=? WHERE id=?";
  }
  $stmt = $conn->prepare($sql);
  if(isAdmin($user)) {
    $stmt->bind_param('si', $content, $id);
  } else {
    $stmt->bind_param('sis', $content, $id, $username);
  }
  $result = $stmt->execute();
  if(!$result) {
    die($conn->error);
  }
  header("location:index.php");
?>