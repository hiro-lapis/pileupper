<?php
// Ajax処理
require('function.php');
debug('Ajax.phpの処理を開始');
debugLogStart();

if(isset($_POST['recordId'])){
  debug('POST送信があります。');
  $record_id = $_POST['recordId'];
  debug('レコードID：'.$record_id);

  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM nice WHERE todo_id = :todo_id AND user_id = :u_id';
    $data = array(':u_id' => $_SESSION['user_id'], ':todo_id' => $record_id );
    $stmt = queryPost($dbh, $sql, $data);

    $result = $stmt->rowCount();
    //ボタンを押したユーザーのniceレコードがある場合
    if(!empty($result)){
      debug('niceがある状態でボタンが押されたのでOFFにします');
      $sql = 'DELETE FROM nice WHERE todo_id = :todo_id AND user_id = :u_id';
      $data = array(':u_id' => $_SESSION['user_id'] ,':todo_id' => $record_id);
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('nice削除完了');
      }


    } else {
      debug('niceがない状態でボタンが押されたのでONにします');
      $sql = 'INSERT INTO nice (user_id, todo_id, create_date) VALUES (:u_id, :todo_id, :create_date)';
      $data = array(':u_id' => $_SESSION['user_id'], ':todo_id' => $record_id, ':create_date' => date('Y-m-d H:i:s'));
      $stmt = queryPost($dbh, $sql, $data);
      if($stmt){
        debug('nice登録完了');
      }

    } echo getNiceNum($record_id);
    //nice数をカウントし、ajax通信の戻り値として返す

  } catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
  }
}
debug('Ajax処理終了');
?>
