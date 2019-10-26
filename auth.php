<?php
debugLogStart();

if(!empty($_SESSION['login_date']) ){
  debug('ログイン済みユーザーです');

  //現在日時が最終ログイン日時＋有効期限を超えていた場合
  if( $_SESSION['login_date'] + $_SESSION['login_limit'] < time()){
    debug('ログイン期限オーバーです');
    session_destroy();
    header("Location:top.php");

  } else {

    debug('ログイン有効期限内です');
    //ログイン日時を更新
    $_SESSION['login_date'] = time();
    /* loginからmypageへアクセスする時にauthの処理が走ると無限ループが走ってしまう。
       basename関数を使ってmypageへ遷移するのはlogin.phpから遷移してきた時のみに限定することでループを回避する。
       mypage以外のファイルでauthを実行した場合nは上のlogin_dateの更新のみが行われる。
    */
    if(basename($_SERVER['PHP_SELF']) === 'top.php'){
      debug('インデックスへ遷移します');
      header("Location:index.php");
    }
  }

  } else {
  debug('未ログインユーザーです。');
  if(basename($_SERVER['PHP_SELF']) !== 'top.php'){
  header("Location:top.php");
  }
}
?>
