<?php
// TODO: リファクタ 現時点では未定だが、websocketを実装してみたい
// https://qiita.com/okumurakengo/items/c497fba7f16b41146d77
debug('メッセージ処理開始');

if(!empty($_POST)){
  debug('POST送信があります。');
  $msg = $_POST['msg'];

  if(!$_SESSION['user_id']){
      $err_msg['common'] = 'ログインしてください';
  } elseif ($_SESSION['login_date'] + $_SESSION['login_limit'] < time()){
      $err_msg['common'] = 'ログインしなおしてください';
  }

  if(empty($err_msg)){
  validRequired($msg, 'msg');
}
  if(empty($err_msg)){
  validMaxMbLen($msg, 'msg', 140);
}
  if(!empty($err_msg)){
      debug('エラーメッセージ：'.print_r($err_msg));
  }
//jsでバリデーション（ボタン非活性）
  if(empty($err_msg)){
    debug('バリデーションOKです。');
    try {

      $dbh = dbConnect();
      $sql = 'INSERT INTO message (todo_id, msg, send_date, from_user )
      VALUES (:todo_id, :msg, :send_date,:from_user)';
      $data = array(':todo_id' => $record_id, ':msg' => $msg, ':send_date' => date('Y-m-d H:i:s'), ':from_user' => $_SESSION['user_id']);
      //クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      //クエリ成功の場合
      if($stmt){
        $msg_id = $dbh->lastInsertId();
        debug('このメッセージのID:'.$msg_id);

        $sql = 'SELECT * FROM message WHERE id = :id' ;
        $data = array(':id' => $msg_id);
        //クエリ実行
        $stmt = queryPost($dbh, $sql, $data);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
      }

    } catch (Exception $e) {
      error_log('エラー発生：'.$e->getMessage());
      $err_msg['common'] = MSG07;
    }
  } debug('バリデーションに引っかかったため処理を中断しました');
} else {
  debug('POST送信がありません');
}
?>
