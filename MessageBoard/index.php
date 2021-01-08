<?php
  session_start();
  require_once('conn.php');
  require_once('utils.php');
  
  $username = null;
  $user = null;
  if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user = getUserFromUsername($username);
  } else {
    $user['role'] = null;
  }
  
  $limit = 5;
  $page = 1;
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
  }
  $offset = ($page - 1) * $limit;
  $sql = 
    "SELECT " .
    "bryan9411_comments.id, bryan9411_comments.username, bryan9411_comments.content, bryan9411_comments.created_at, bryan9411_users.nickname " .
    "FROM bryan9411_comments left join bryan9411_users on " .
    "bryan9411_comments.username = bryan9411_users.username " .
    "WHERE bryan9411_comments.is_deleted is NULL " .
    "ORDER BY bryan9411_comments.id DESC " .
    "limit ? offset ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('ii', $limit, $offset);
  $result = $stmt->execute();
  if(!$result) {
    die($conn->error);
  }
  $result = $stmt->get_result();
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
    <?php if (!$username) {?>
    <a href="register.php" class="board__btn">註冊</a>
    <a href="login.php" class="board__btn">登入</a>
    <h1 class="board__title">Comments</h1>
    <div class="login__n">請登入帳號發布留言</div>
    <?php } else {?>
    <a href="logout.php" class="board__btn">登出</a>
    <a href="" class="board__edit-btn">編輯暱稱</a>
    <?php if ($user['role'] == 'admin') {?>
      <a href="backstage.php" class="board__edit-btn">後台管理</a>
    <?php }?>
    <form method="POST" action="update_user.php" class="board__new-comment-form hide">
      <span>新的暱稱:</span>
      <input type="text" name="nickname"> 
      <input class="board__submit-btn" type="submit" value="送出">
    </form>
    <h1 class="board__title">
      <?php echo '你好' . '<span class="login__nickname">' . $user['nickname'] . '</span>'?>
    </h1>
    <form class="board__new-comment-form" method="POST" action="handle_add_comment.php">
      <textarea name="content" rows="5"></textarea>
      <?php if ($username && !hasPermission($user, 'create', null)) {?>
      <h3 class="banned">已停權帳戶</h3>
      <?php } else if($username) { ?>
      <input class="board__submit-btn" type="submit">
      <?php }?>
    </form>
    <?php }?>
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
    <div class="board__hr"></div>
    <section class="board__data">
    <?php while ($row = $result->fetch_assoc()) {?>
      <div class="card">
        <div class="card__member-face">
          <img src="" alt="">
        </div>
        <div class="card__info">
          <div class="card__info-message">
            <div class="card__title">
            <?php echo escape($row['nickname'])?>
            (@<?php echo escape($row['username'])?>)
          </div>
            <div class="card__date"><?php echo escape($row['created_at'])?></div>
          </div>
          <div class="card__content"><?php echo escape($row['content'])?></div>
        </div>
        <div class="card__features">
          <div class="card__edit">
          <?php if (hasPermission($user, 'update', $row)) {?>
            <a href="update_comment.php?id=<?php echo $row['id']?>">
              編輯
            </a>
          <?php }?>
          </div>
          <div class="card__delete">
          <?php if (hasPermission($user, 'delete', $row)) {?>
            <a href="handle_delete.php?id=<?php echo $row['id']?>">
              刪除
            </a>
          <?php }?>
          </div>
        </div>
      </div>
    <?php }?>
    </section>
    <div class="board__hr"></div>
    <?php
      $sql = "SELECT count(id) FROM bryan9411_comments WHERE is_deleted IS NULL";
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();
      $count = $row['count(id)'];
      $total_page = ceil($count / $limit);
    ?>
    <div class="board__page">
      <span>總共有<?php echo $count ?> 筆資料，頁數: </span>
      <span><?php echo $page ?> / <?php echo $total_page ?> 頁</span>          
      <div class="board__page-info">
      <?php if ($page != 1) {?>
        <a href="index.php?page=1">首頁</a>
        <a href="index.php?page=<?php echo $page - 1?>">上一頁</a>
      <?php }?>
      <?php if ($page != $total_page) {?>
        <a href="index.php?page=<?php echo $page + 1?>">下一頁</a>
        <a href="index.php?page=<?php echo $total_page?>">最末頁</a>
      <?php }?>
      </div>
    </div>
  </main>

  <script>
      document.querySelector('.board__edit-btn').addEventListener('click', function(e) {
        e.preventDefault();
        let form = document.querySelector('.board__new-comment-form')
        form.classList.toggle('show')
      })
  </script>
</body>

</html>