<?php
require('function.php');
if(!empty($_POST)){
  $name = $_POST['name'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

  //未入力バリデーション
  validRequired($name, 'name');
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');
  if(empty($err_msg)){
    //name
    validMaxLen($name, 'name', 20);
    //email
    validEmail($email, 'email');
    validMaxLen($email, 'email');
    validEmailDup($email);
    //password
    validHalf($pass, 'pass');
    validMaxLen($pass, 'pass');
    validMinLen($pass, 'pass');
    //再入力
    validMaxLen($pass_re, 'pass_re');
    validMinLen($pass_re, 'pass_re');
    validMatch($pass, $pass_re, 'pass_re');

    if(empty($err_msg)){
      try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO users (name,email,password,login_time,create_date) VALUES(:name,:email,:pass,:login_time,:create_date)';
        $data = array(':name' => $name,':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
        ':login_time' => date('Y-m-d H:i:s'),
        ':create_date' => date('Y-m-d H:i:s'));
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
          debug('アカウント作成');
          //ログイン有効期限（1h）
          $sesLimit = 60*60;
          $_SESSION['login_date'] = time();
          $_SESSION['login_limit'] = $sesLimit;
          // ユーザーID格納
          $_SESSION['user_id'] = $dbh->lastInsertId();
          header("Location:index.php");
        }
      } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = MSG07;
      }
    }
  }
}
?>
<?php
$siteTitle = 'アカウント作成';
require('head.php');
?>
<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>
  <main id="main">
    <section class="c-container--md">
      <h2 class="c-title">アカウント作成</h2>
      <form class="c-form p-form--signup"  method="post">
        <div class="p-msg--err">
          <span class="err__msg"><?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></span>
        </div>
        <span class="c-msg__err"><?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?></span>
        <input type="text" class="c-input p-input" name="name" placeholder="ユーザー名" value="<?php if(!empty($POST['name'])) echo $_POST['name']; ?>" required>

        <span class="c-msg__err"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
        <input type="email" class="c-input p-input" name="email" placeholder="Email" value="<?php if(!empty($POST['email'])) echo $_POST['email']; ?>" required>

        <span class="c-msg__err"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></span>
        <input type="password" class="c-input p-input" name="pass" placeholder="パスワード" value="<?php if(!empty($POST['pass'])) echo $_POST['pass']; ?>" autocomplete="new-password" required>

        <span class="c-msg__err"><?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?></span>
        <input type="password" class="c-input p-input" name="pass_re" placeholder="パスワード（再入力）" value="<?php if(!empty($POST['pass_re'])) echo $_POST['pass_re']; ?>" required>

        <input type="submit" class="c-btn--wide c-btn--center p-btn--submit" name="" value="アカウント作成">
      </form>
    </section>
  </main>
  <?php
  require('footer.php');
  ?>
