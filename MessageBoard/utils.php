<?php
  // session_start();
  require_once('conn.php');
  function getUserFromUsername($username) {
    global $conn;
    $sql = "SELECT * FROM bryan9411_users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $result = $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row;
  }

  function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES);
  }

  function hasPermission($user, $action, $comment) {
    if ($user['role'] === 'admin') {
      return true;
    }
    if ($user['role'] === 'normal') {
      if ($action === 'create') return true;
      return $comment['username'] === $user['username'];
    }
    if ($user['role'] === 'banned') {
      if ($action === 'create') return false;
      return $comment['username'] === $user['username'];
    }
  }

  function isAdmin($user) {
    return $user['role'] === 'admin';
  }
?>