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
    <a href="index.php" class="board__btn">回到留言板</a>
    <a href="login.php" class="board__btn">登入</a>
    <h1 class="board__title">註冊</h1>
    <?php
      if (!empty($_GET['errCode'])) {
        $code = $_GET['errCode'];
        $message = "Error";
        if ($code === '1') {
          $message = "資料不齊全";
          echo '<span class="error">'. $message .'</span>';
        } else if ($code === '2') {
          $message = "帳號已被註冊";
          echo '<span class="error">'. $message .'</span>';
        }
      }
    ?>
    <form class="board__new-comment-form" method="POST" action="handle_register.php">
      <div class="board__nickname">
        <span>暱稱:</span>
        <input type="text" name="nickname" id="">
      </div>
      <div class="board__nickname">
        <span>帳號:</span>
        <input type="text" name="username"" id="">
      </div>
      <div class=" board__nickname">
        <span>密碼:</span>
        <input type="password" name="password" id="">
      </div>
      <input class="board__submit-btn" type="submit">
    </form>
  </main>
</body>
</html>