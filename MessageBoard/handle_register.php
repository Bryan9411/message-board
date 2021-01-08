<?php
  require_once('conn.php');
  $nickname = $_POST['nickname'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  
  if(empty($_POST['nickname']) || empty($_POST['username']) || empty($_POST['password'])) {
    header("location:register.php?errCode=1");
    die();
  }
  $sql = "INSERT INTO bryan9411_users(nickname, username, password) VALUES(?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('sss', $nickname, $username, $password);
  $result = $stmt->execute();
  if (!$result) {
    // 找出錯誤代碼
    $code = $conn->errno;
    if ($code === 1062) {
      header("location:register.php?errCode=2");
      die();
    }
    die($conn->error);
  }
  header("location:index.php");
?>