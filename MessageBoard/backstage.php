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
  // 權限檢查
  if ($user === null || $user['role'] !== 'admin') {
    header("location:index.php");
    exit();
  }
  $limit = 5;
  $page = 1;
  if (!empty($_GET['page'])) {
    $page = intval($_GET['page']);
  }
  $offset = ($page - 1) * $limit;
  $sql = "SELECT id, role, nickname, username FROM bryan9411_users order by id asc";
  $stmt = $conn->prepare($sql);
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
  <title>後台管理</title>
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
    <?php if ($user['role'] == 'admin') {?>
      <a href="index.php" class="board__edit-btn">回到留言板</a>
      <!-- <a href="backstage.php" class="board__edit-btn">後台管理</a> -->
    <?php }?>
    <table class="backstage__table"> 
      <tr> 
        <th>id</th> 
        <th>權限</th>
        <th>暱稱</th>
        <th>使用者帳號</th>
        <th>權限管理</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) {?>
      <tr> 
        <td><?php echo escape($row['id'])?></td> 
        <td><?php echo escape($row['role'])?></td> 
        <td><?php echo escape($row['nickname'])?></td>
        <td><?php echo escape($row['username'])?></td>
        <td>
          <form method="POST" action="handle_update_role.php">
            <select name="role">
              <option>admin</option>
              <option>normal</option>
              <option>banned</option>
            </select>
            <input type="hidden" name="username" value="<?php echo escape($row['username'])?>">
            <input type="submit" name="changeRole"  class="role__btn" value="儲存">
          </form>
        </td>
      </tr>
      <?php }?>
</table>
    
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
    </div>
  </main>
</body>

</html>