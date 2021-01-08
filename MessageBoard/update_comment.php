<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  
  $id = $_GET['id'];
  $username = null;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
  }

  $sql = "SELECT * FROM bryan9411_comments WHERE id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('i', $id);
  $result = $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>留言板</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="fontawesome-free-5.14.0-web/css/all.css">
</head>

<body>
  <header>
    <p>
      注意!本站為練習用網站，因教學用途刻意忽略資安的實作，註冊時請勿使用任何真實的帳號或密碼。
    </p>
  </header>
  <main class="board">
    <h1 class="board__title">編輯留言</h1>
    <form class="board__new-comment-form" method="POST" action="handle_update_comment.php">
      <textarea name="content" rows="5"><?php echo escape($row['content'])?></textarea>
      <input type="hidden" name="id" value="<?php echo escape($row['id'])?>">
      <input class="board__submit-btn" type="submit">
    </form>
    <?php
      if (!empty($_GET['errCode'])) {
        $code = $_GET['errCode'];
        $message = "Error";
        if ($code === '1') {
          $message = "請輸入內容";
          echo '<span class="error">'. $message .'</span>';
        }
      }
    ?>
  </main>
</body>

</html>