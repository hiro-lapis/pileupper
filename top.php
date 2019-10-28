<?php
require('function.php');
if(!empty($_POST)){
  $email = $_POST['email'];
  $pass =$_POST['pass'];
  $pass_save =(!empty($_POST['pass_save'])) ? true : false;
  //emailバリデーション
  validRequired($email, 'email');
  validEmail($email, 'email');
  validMaxLen($email, 'email');

  //pass_wordバリデーション
  validRequired($pass, 'pass');
  validHalf($pass, 'pass');
  validLength($pass, 'pass');

  if(empty($err_msg)){
    debug('バリデーションクリア');

    try {
      $dbh = dbConnect();
      $sql = 'SELECT password,id FROM users WHERE email = :email AND delete_flg = 0';
      $data = array(':email' => $email);
      $stmt = queryPost($dbh, $sql, $data);

      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      debug('クエリ結果の中身：' . print_r($result, true));

      //パスワード照合
      if(!empty($result) && password_verify($pass, array_shift($result))){
        debug('パスワードがマッチ！');
        //ログイン有効期限デフォルト(1h)
        $sesLimit = 60 * 60;
        $_SESSION['login_date'] = time();
        //ログイン保持にチェックがある場合(2週間)
        if($pass_save){
          debug('ログイン省略ON');
          $_SESSION['login_limit'] = $sesLimit * 24 * 14;
        } else {
          //ログイン保持にチェックがない場合(1h)
          debug('ログイン省略OFF');
          $_SESSION['login_limit'] = $sesLimit;
        }
        //DB照合したユーザーIDを格納
        $_SESSION['user_id'] = $result['id'];
        debug('セッション変数の中身：'.print_r($_SESSION,true));
        debug('マイページへ遷移します。');
        header('Location:index.php');
        //例外処理
      } else {
        debug('パスワードがマッチしません。');
        $err_msg['common'] = MSG09;
      }
    } catch (Exception $e) {
      error_log('エラー発生：' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
?>
<?php
$siteTitle = 'トップページ';
require('head.php');
?>

<body>
  <!-- ヘッダー -->
  <?php
  require('header.php');
  ?>
  <main id="main">
    <!-- ログインフォーム -->
    <section class="c-container--md">
        <h2 class="c-title">ログイン</h2>
            <form class="c-form p-form--login" method="post">
                <div class="p-form--login__body">

                    <span class="c-msg__err"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
                    <input type="email" class="c-input p-input" name="email" placeholder="Eメール" value="<?php if(!empty($POST['eamil'])) echo $_POST['email']; ?>" required>
                    <span class="c-msg__err"><?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?></span>
                        <input type="password" class="c-input p-input" name="pass" placeholder="パスワード(6~20文字)" value="<?php if(!empty($POST['pass'])) echo $_POST['pass']; ?>" required>

                    <span class="c-msg__err"><?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></span>
                </div>
                <div class="p-form--login__bottom">
                    <input type="submit"  class="c-btn--large p-btn--submit" value="ログイン">
                    <a href="passRemind.php" class="c-form__link p-text-link--pass-reminder">パスワードを忘れてしまった方はこちら</a>
                </div>

            </form>
        </div>
    </section>
  </main>
  <!-- フッター -->
  <?php
  require('footer.php');
  ?>
