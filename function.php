<?php
// TODO: リファクタ
//ログをとるように設定、log.phpに表示
ini_set('log_errors', 'On');
ini_set('error_log', 'php.log');

//定数（エラー・サクセス)メッセージの定義
define('MSG01', '入力必須です');//validRequired
define('MSG02', 'Eメールアドレスを入力してください');//validEmail
define('MSG03', 'パスワードと再入力が一致しません');//validMatch
define('MSG04', '半角英数字で入力してください');//validNumber
define('MSG05', 'パスワードは６文字以上で設定してください');//validMinLen
define('MSG06', '最大文字数は140字までです');//validMaxLen
define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEMAILはすでに登録されています。');
//投稿フォームのMSG
define('MSG09', 'アドレスかパスワードが間違っています。');
define('MSG10', 'メッセージは100文字以内にしてください');
define('MSG11', 'そのユーザー名はすでに登録されています。');
define('MSG12', '古いパスワードが登録されたパスワードと一致しません。');
define('MSG13', '新しいパスワードが古い方と同じです。');
define('MSG14', '文字以上入力してください。');
define('MSG15', '認証キーが違います。');
define('MSG16', 'カテゴリーを選択してください。');



//成功MSG
define('SUC01', 'パスワードを変更しました。');
define('SUC02', 'プロフィールを更新しました。');
define('SUC03', 'メールを送信しました。');
define('SUC04', '新規タスクを登録しました。');
define('SUC05', 'メッセージを送信しました。');

//レコード投稿メッセージ
define('MUS01', '二頭が良いね！チョモランマ！');
define('MUS02', '岩をも砕く三頭筋!');
define('MUS03', '肩にちっちゃい重機のっけてんのかい！？');
define('MUS04', '大胸筋が歩いてる！？');
define('MUS05', '腹筋６LDKかい！');
define('MUS06', '背中に鬼神が宿ってる！');
define('MUS07', '出たな！プロポーションオバケ！');
define('MUS08', '泣く子も黙る大腿筋！');
define('MUS09', 'とってもジューシーハムストリングス！');
define('MUS10', '翼を授けるヒラメ筋');


//エラーメッセージの箱となる変数を用意
$err_msg = array();

//================
//デバッグログのメソッド
$debug_flg = true;
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}
//デバッグ処理開始時に表示
function debugLogStart(){
  debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID：'.session_id());
  debug('セッション変数の中身：'.print_r($_SESSION,true));
  debug('現在日時タイムスタンプ：'.time());
  if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
    debug( 'ログイン期限日時タイムスタンプ：'.( $_SESSION['login_date'] + $_SESSION['login_limit'] ) );
  }
}

// セッション準備・セッション有効期限を延ばす
//================================
//セッションファイルの置き場を変更する（/var/tmp/以下に置くと30日は削除されない）
session_save_path("/var/tmp/");
//ガーベージコレクションが削除するセッションの有効期限を設定（30日以上経っているものに対してだけ１００分の１の確率で削除）
ini_set('session.gc_maxlifetime', 60*60*24*30);
//ブラウザを閉じても削除されないようにクッキー自体の有効期限を延ばす
ini_set('session.cookie_lifetime ', 60*60*24*30);
//セッションを使う
session_start();
//現在のセッションIDを新しく生成したものと置き換える（なりすましのセキュリティ対策）
session_regenerate_id();
//バリデーションメソッド
//ユーザー登録
//バリデーション①（未入力）チェック
function validRequired($str, $key){
  if(empty($str)){
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
//バリデーション②EMAIL形式チェック
function validEmail($str,$key){
  if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
function validLength($str, $key, $len = 8){
  if(strlen($str) !== $len){
    global $err_msg;
    $err_msg[$key] = $len.MSG14;
  }
}
//バリデーション③英数字最大文字数チェック(strlen)
//数字のみ入力箇所（email,pass)
function validMaxLen($str,$key,$max = 255){
  if(strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
//バリデーション③文章最大文字数チェック(mb_strlen)
//ひらがな等入力箇所（name,msg,)
function validMaxMbLen($str,$key,$max = 140){
  if(mb_strlen($str) > $max){
    global $err_msg;
    $err_msg[$key] = '最大文字数は'.$max.'字までです';
  }
}
//バリデーション④最小文字数チェック
function validMinLen($str,$key,$min = 6){
  if(strlen($str) < $min){
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
//バリデーション⑤半角英数字チェック
function validHalf($str, $key){
  if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
//バリデーション⑥パスワード再入力のチェック
function validMatch($str1,$str2,$key){
  if( $str1 !== $str2){
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
//バリデーション⑦カテゴリ入力のチェック
function validSelect($str, $key){
  if(!preg_match("/^[0-9]+$/", $str) || $str === 0){
    global $err_msg;
    $err_msg[$key] = MSG16;
  }
}
//エラーメッセージ表示
//エラーメッセージがある場合、引数を戻り値でエラーメッセージのキーとして返す
function getErrMsg($key){
  global $err_msg;
  if(!empty($err_msg[$key])){
    return $err_msg[$key];
  }
}
//バリデーション⑧email重複登録のチェック
function validEmailDup($email){
  global $err_msg;
  try {

    $dbh = dbConnect();
    //入力されたemailの値を持ち、アカウントが有効なものを検索。
    $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
    $data = array(':email' => $email);

    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //配列の先頭を取り出す関数で、クエリ結果の頭を取得
    if(!empty(array_shift($result))){
      $err_msg['email'] = MSG08;
    }
  }catch (Exception $e){
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}


//接続関連
//DBへの接続準備
function dbConnect(){
  $dsn = 'mysql:dbname=pileupper;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $options =array(
    //SQL実行時にはエラーコードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    //バッファードクエリを使う（一度に結果セットを全て取得し、サーバー負荷を軽減）
    //SELECTで得た結果に対してもrowCountメソッドも使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  //PDOオブジェクト生成
  $dbh = new PDO($dsn, $user,$password, $options);
  return $dbh;
}
//クエリ作成関数
function queryPost($dbh, $sql, $data){

  $stmt = $dbh->prepare($sql);
  //プレースホルダに値をセットし、SQL文を実行
  if(!$stmt->execute($data)){
    debug('クエリに失敗しました。');
    $err_msg['common'] = MSG07;
    return 0;
  }

  debug('クエリ成功');
  return $stmt;
}

//ユーザー情報取得関数
function getUser($u_id){
  debug('ユーザー情報を取得します。');

  try {

    $dbh = dbConnect();
    $sql =  'SELECT * FROM users WHERE id = :u_id AND delete_flg = 0';
    $data = array( ':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else {
      return false;
    }

  } catch (Exception $e){
    error_log('エラー発生:' . $e->getMessage());
  }
}
//todoリスト情報取得関数
function getToDo($u_id, $record_id){
  debug('todoリスト情報を取得します。');
  debug('ユーザーID:'.$u_id);
  debug('todoID'.$record_id);
  //例外処理
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM todo WHERE user_id = :u_id AND id = :t_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id, ':t_id' => $record_id);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果のデータを一つだけ取得
      return $stmt -> fetch(PDO::FETCH_ASSOC);
    } else {
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e-> getMessage());
  }
}
//カテゴリ取得関数
function getCategory(){
  debug('カテゴリ 情報を取得します。');

  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM category';
    $data = array();

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      return $stmt->fetchAll();
    }else{
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}
//todo詳細情報取得関数
function getToDoOne($record_id){
  debug('詳細なtodo情報を取得します。');
  debug('TodoのID:'.$record_id);

  //例外処理
  try {
    //DBへ接続
    $dbh =dbConnect();
    $sql = 'SELECT t.id , t.name , t.comment, t.pic1, t.user_id, t.create_date, t.update_date, c.name AS category
    FROM todo AS t LEFT JOIN category AS c ON t.category_id = c.id WHERE t.id = :t_id AND t.delete_flg = 0 AND c.delete_flg = 0';
    $data = array(':t_id' => $record_id);

    //クエリ実行
    debug('SQL:'.$sql);
    $stmt = queryPost($dbh, $sql, $data);
    debug('クエリ返却結果:'.print_r($stmt, true));

    if($stmt){
      debug('クエリ成功:情報を取得します。');
      return $stmt->fetch(PDO::FETCH_ASSOC);
    } else {

      return false;
    }

  } catch(Exception $e){
    error_log('エラー発生：'.$e->getMessage());
  }
}
//メッセージ取得関数
function getMsg($record_id){
  debug('メッセージ情報を取得します');
  try {
    $dbh = dbConnect();
    $sql = 'SELECT m.msg, m.send_date, m.from_user, u.name AS name , u.pic AS user_image
    FROM message AS m LEFT JOIN users AS u ON m.from_user = u.id WHERE todo_id = :t_id AND m.delete_flg = 0 ORDER BY send_date DESC';
    $data = array(':t_id' => $record_id);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      debug('クエリ成功');
      return $stmt->fetchAll();
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生:'.$e->getMessage());
  }
}
//お気に入り登録チェック関数
function isNice($u_id, $record_id){
  debug('niceがあるか確認します。');
  debug('レコードID：'.$record_id);
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM nice WHERE todo_id = :todo_id AND user_id = :u_id';
    $data = array( ':todo_id' => $record_id, ':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);
    if($stmt->rowCount()){
      debug('niceがあります。');
      return true;
    } else {
      debug('niceはありません。');
      return false;
    }
  } catch (Exception $e){
    error_log('エラー発生：'.$e -> getMessage());
  }
}
//お気に入りデータ取得関数①
function getMyNice($u_id){
  debug('ユーザーのID'.$u_id);
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM nice WHERE todo_id = :todo_id LEFT JOIN todo AS t ON 1.todo_id = t.id WHERE 1.user_id = :u.id ';
    $data = array( ':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);
  } catch (Exception $e){
    error_log('エラー発生：'.$e -> getMessage());
  }
}
//todoリスト取得関数
function getToDoList($currentMinNum = 1, $category, $sort, $span =10){
  debug('Todo情報を取得します');
  try {
    //DBへ接続
    $dbh =  dbConnect();
    //件数用のSQL作成
    $sql =  'SELECT id FROM todo';
    if(!empty($category)) $sql .=' WHERE category_id = '.$category;
    if(!empty($sort)){
      switch ($sort) {
        case 1:
        $sql .=' ORDER BY create_date ASC';
        debug('新しいレコード順に取得します。');
        break;
        case 2:
        $sql .=' ORDER BY create_date DESC';
        debug('古いレコード順に取得します。');
        break;
      }//検索ページでは１、２それぞれのクエリ文に対応したセレクトボックスを作成する
    }

    $data = array();
    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount();//総レコード数
    $rst['total_page'] = ceil($rst['total']/$span);//レコード数をspanで割るとページ数になる
    debug('総レコード数：'.$rst['total']);
    debug('総ページ数：'.$rst['total_page']);

    if(!$stmt){
      return false;
    }

    //ページング用のSQL作成.todoテーブルの全カラムを対象に検索条件を元にレコードを取ってくる
    $sql = 'SELECT t.id, t.name, t.user_id, t.pic1, t.create_date, c.name AS category, u.name AS user, u.pic AS user_image
    FROM todo AS t  LEFT JOIN category AS c ON t.category_id = c.id
    LEFT JOIN users    AS u ON t.user_id = u.id';

    if(!empty($category)) $sql .=' WHERE category_id = '.$category;
    if(!empty($sort)){
      switch ($sort) {
        case 1:
        $sql .=' ORDER BY create_date ASC';
        debug('新しいレコード順に取得します。');
        break;
        case 2:
        $sql .=' ORDER BY create_date DESC';
        debug('古いレコード順に取得します。');
        break;
      }
    }
    $sql .= ' LIMIT '.$span.' OFFSET '.$currentMinNum;

    $data = array();
    debug('SQL:'.$sql);
    //クエリ実行
    $stmt =  queryPost($dbh, $sql, $data);

    if($stmt){
      //クエリ結果のデータを全レコードを格納
      $rst['data'] = $stmt->fetchAll();
      //ここでdataというキーが当たっているから、mypageのdbToDoDataにもdataというキーが付いている。
      debug('このページで表示する情報一覧:'.print_r($rst, true));
      return $rst;
    } else {
      return false;
    }
  } catch (Exception $e) {
    error_log('エラー発生；'.$e->getMessage());

  }
}
//お気に入りデータ取得関数①
function getNiceNum($record_id){
  debug('レコードID:'.$record_id);
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM nice WHERE todo_id = :todo_id';
    $data = array( ':todo_id' => $record_id);

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      return $result = $stmt->rowCount();
    }
  } catch (Exception $e){
    error_log('エラー発生：'.$e -> getMessage());
  }
}
//ユーザーのレコード取得関数
function getMy_Record($u_id){
  debug('レコードを取得するユーザーのID'.$u_id);
  try {
    $dbh = dbConnect();
    $sql = 'SELECT * FROM todo WHERE user_id = :u_id AND delete_flg = 0';
    $data =  array(':u_id' => $u_id);

    $stmt = queryPost($dbh, $sql, $data);
    if($stmt){
      debug('レコード取得成功！');
      $result = $stmt->fetchAll();
      debug('取得したレコード'.print_r($result));
    }
  } catch (Exception $e){
    error_log('エラー発生：'.$e->getMessage);
  }
}
//ページネーション関数
//$currentPageNum 現在のページNo
function pagination( $currentPageNum, $totalPageNum, $link = '', $pageColNum = 5){
  //$currentMinNum  総ページ数
  //$link           検索用GETパラメータリンク
  //$pageColNum     ページネーション表示数 デフォルト５

  //現在のページが、総ページ数と同じかつ総ページ数が表示項目数以上なら、左にリンク４個出す
  if($currentPageNum == $totalPageNum && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum - 4;
    $maxPageNum = $currentPageNum;
    //現在のページが総ページ数の一つ前なら左にリンク３個、右にリンク１個出す
  }elseif($currentPageNum == ($totalPageNum - 1) && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum - 3;
    $maxPageNum = $currentPageNum + 1;
    //現在のページが２の場合は左にリンク１個、右に３つ出す。
  }elseif($currentPageNum == ($totalPageNum - 2) && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum - 1;
    $maxPageNum = $currentPageNum +3;
    //現ページが１の場合は左に１個も出さず、右に５つ出す。
  }elseif($currentPageNum == 1 && $totalPageNum >= $pageColNum){
    $minPageNum = $currentPageNum;
    $maxPageNum = 5;
    //現ページが表示項目数よりも少ない場合は、総ページ数をループのMax、ループのMinを１に設定
  }elseif($totalPageNum < $pageColNum){
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
    //それ以外は左に２個出す。
  }else{
    $minPageNum = $currentPageNum -2;
    $maxPageNum = $currentPageNum +2;
  }
  //総ページ数が表示項目数よりも少ない場合は、総ページ数をループのMax、ループのMinを１に設定
  if ($totalPageNum < $pageColNum){
    $minPageNum = 1;
    $maxPageNum = $totalPageNum;
  }

  echo '<div class="pagenation">';
  echo '<ul class="pagenation-list">';
  if($currentPageNum != 1){
    echo '<li class="list-item"><a href="?p=1">&lt;</a></li>';
  }
  for($i = $minPageNum; $i<= $maxPageNum; $i++){
    echo '<li class="list-item';
    if($currentPageNum == $i ){ echo 'active'; }
    echo '"><a href="?p='.$i.$link.'">'.$i.'</a></li>';
  }
  if($currentPageNum != $maxPageNum){
    echo '<li class="list-item"><a href="?p='.$maxPageNum.$link.'">&gt;</a></li>';
  }
  echo '</ul>';
  echo '</div>';
}
//詳細から一覧へ戻る時のURL調整関数
function appendGetParam($arr_del_key = array()){
  //引数に指定した値を取り除いてくれる
  if(!empty($_GET)){
    $str = '?';
    foreach ($_GET as $key => $val){
      if(!in_array($key, $arr_del_key, true)){
        $str .= $key.='='.$val.'&';
      }
    }
    $str = mb_substr($str, 0, -1, "UTF-8");
    return $str;
  }
}

//----------------------------------
//画像関連関数
//----------------------------------
//画像取得関数
function showImg($path){
  if(empty($path)){
    //画像投稿がない時にサンプル画像を表示する
    return 'img/sample-img.png';
  } else {
    return $path;
  }
}
//画像アップロード関数
function uploadImg($file, $key){
  debug('画像アップロード処理開始');
  debug('FILE情報:'.print_r($file, true));

  if(isset($file['error']) && is_int($file['error'])){
    try {
      //バリデーション
      //$file['error']の値を確認。配列内には「UPLOAD＿ERR＿OK」などの定数が入っている。
      //定数はphpファイルでアップロード時に自動的に定義される、定数には０や１などの数値が入っている。
      switch ($file['error']){
        case UPLOAD_ERR_OK: //OK
        break;

        case UPLOAD_ERR_NO_FILE://ファイル未選択の場合
        throw new RuntimeException('ファイルが選択されていません。');

        case UPLOAD_ERR_INI_SIZE://php.ini定義の最大サイズが超過した場合
        throw new RuntimeException('ファイルサイズが大き過ぎます。');
        case UPLOAD_ERR_FORM_SIZE://フォーム定義の最大サイズを超過した場合
        throw new RuntimeException('ファイルサイズが大き過ぎます。');

        default://その他の場合
        throw new RuntimeException('その他のエラーが発生しました。');
      }
      //$file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前てチェックする。
      //exif_imagetype関数は「IMAGETYPE＿GIF」『IMAGETYPE_JPEG」などの定数を返す
      //先頭に@をつけることでエラーが出た時も処理が止まらないようにする効果がある。
      $type = @exif_imagetype($file['tmp_name']);
      //in_arrayは第一引数の配列に第二引数の要素があるかを判定する関数
      if(!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)){//第３引数にtrueを入れると厳密にチェックしてくれる
        throw new RuntimeException('画像の形式が未対応です。');
      }
      //ファイルデータからSHAーハッシュを取ってファイル名を決定し、ファイルを保存する
      //ハッシュ化しておかないとアップロードされたファイル名そのままで保存、中身の異なる同名のファイルが上がる
      //可能性がある。DBにパスを保村すると区別がつかなくなるリスク
      //image_type_to_extension関数はファイルの拡張子を取得するもの
      //通信時のMIMEの審査にはexif_imagetypeを、ファイルそのものの拡張子の審査にはimage_tyoe_to_extentionを入力する
      $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);

      if(!move_uploaded_file($file['tmp_name'], $path)){//パスを移動する
        throw new RuntimeException('ファイル 保存時にエラーが発生しました。');
      }
      //保存したファイルパスのパーミッション（権限）を変更する
      chmod($path, 0644);

      debug('ファイルは正常にアップロードされました。');
      debug('ファイルファイルパス'.$path);
      return $path;

    } catch (RuntimeException $e) {

      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();

    }
  }
}
///////////////////////////////
//その他
///////////////////////////////
//サニタイズ
function sanitize($str){
  return htmlspecialchars($str, ENT_QUOTES);
}

//フォーム入力保持関数
function getFormData($str, $flg = false){
  if($flg){
    $method = $_GET;
  } else {
    $method = $_POST;
  }
  global $dbFormData;
  //ユーザーデータがある場合
  if(!empty($dbFormData)){
    //エラーメッセージがある場合
    if(!empty($err_msg[$str])){
      //POST送信されている場合
      if(isset($method[$str])){
        //POST送信の値を返す
        return $method[$str];

      } else {
        //エラーはあるがPOSTがない場合、DB情報を返す。通常は新井えない
        return $dbFormData[$str];

      }
    } else {
      //エラーがない場合
      if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
        return $method[$str];
        //POST送信された値がDBの情報と異なる場合、POSTの値を表示
      } else {
        //エラーがなく、かつPOST送信がないのでDBの情報を返す
        return $dbFormData[$str];
      }
    }
  } else {
    if(isset($_POST[$str])){
      return $_POST[$str];
      //DB情報が空でPOSTに情報が入っていた場合、POST情報を返す
    }
  }
}
//ログイン認証
//auth.phpと異なりSESSION更新・遷移なし
function isLogin(){
  if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです。');

    //現在日時が最終ログイン 日時＋有効期限をすぎていた場合
    if( ($_SESSION['login_date'] + $_SESSION['login_limit']) < time()){
      debug('ログイン有効期限切れです。');
      return true;
    }
  } else {
    debug('未ログインユーザーです。');
    return false;
  }
}
//認証キー生成
function makeRandKey($length = 8){
  $chars ='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
  $str = '';
  for ($i = 0; $i < $length; ++$i){
    //文字列変数[数字]で文字列の一文字を取得できる
    // . をつけることでくり返し出力する文字列が連結される
    $str .= $chars[mt_rand(0, 61)];
  }
  return $str;
}

//メールアドレス変更後のメール送信関数
function sendMail($from, $to, $subject, $comment){
  if(!empty($to) && !empty($subject) && !empty($comment)){
    //文字化け防止
    mb_language("Japanese");//現在使っている言語を設定
    mb_internal_encoding("UTF-8");//PC側でのエンコーディングの設定

    $result = mb_send_mail($to, $subject, $comment, "From: ".$from);
    //送信結果を表示
    if($result) {
      debug('メールを送信しました。');
    } else {
      debug('エラー発生：メールの送信に失敗しました。');
    }
  }
}
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}

?>
