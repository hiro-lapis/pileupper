<?php
  require('function.php');
  require('auth.php');
  debug('退会処理');
  debugLogStart();

  if(!empty($_POST['withdraw'])){
    debug('退会ボタンが押されました');
    try{
      $dbh = dbConnect();
      $sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
      $sql2 = 'UPDATE todo SET delete_flg = 1 WHERE id = :us_id';
      $data = array(':us_id' => $_SESSION['user_id']);

      $stmt1 = queryPost($dbh, $sql1, $data);
      $stmt2 = queryPost($dbh, $sql2, $data);

      if($stmt1){
        session_destroy();
        debug('セッション変数の中身：'. print_r($_SESSION, true));
        debug('トップページへ遷移');
        header('Location:top.php');
      } else {
        debug('クエリが失敗しました。');
        $err_msg['common'] = MSG07;
      }

    } catch (Exception $e) {
      error_log('エラー発生；'. $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
  debug('退会判定・処理終了');
?>
