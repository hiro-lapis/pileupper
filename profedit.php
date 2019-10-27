<?php
require('function.php');
require('auth.php');
debugLogStart();
debug('プロフィール変更ページです。');

//ユーザーデータ取得
$dbFormData = getUser($_SESSION['user_id']);
debug('ユーザー情報：'.print_r($dbFormData,true));


//POST処理１:プロフィール設定
if(!empty($_POST)){
    debug('POST送信あり');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報；'.print_r($_FILES,true));
    $name = $_POST['name'];
    $email = $_POST['email'];
    //画像送信があるかチェック。アップロード
    $pic = ( !empty($_FILES['pic']['name']) ) ? uploadImg($_FILES['pic'], 'pic') : '';
    //画像僧院なしで既に登録済の場合は、DBの画像を表示する
    $pic = ( empty($pic) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] :  $pic;

    if($dbFormData['name'] !== $name){
        //nameバリデーション
        validRequired($name, 'name');
        validMaxLen($name, 'name');
    }
    if($dbFormData['email'] !== $email){
        //emailバリデーション
        validMaxLen($email, 'email');
        validEmailDup($email);
    }

    if(empty($err_msg)){
        validRequired($email, 'email');
        validEmail($email, 'email');
    }

    if(empty($err_msg)){
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE users SET name =:u_name, email = :email, pic = :pic WHERE id = :u_id';
            $data = array(':u_name' => $name, ':email' => $email, ':pic' => $pic, ':u_id' => $dbFormData['id']);
            $stmt = queryPost($dbh, $sql, $data);

            if($stmt){
                debug('クエリ成功です。');
                debug('マイページへ遷移します。');
                $_SESSION['msg_success'] = SUC02;
                header("Location:index.php");
            }
        } catch (Exception $e) {
            debug('エラー発生：'. $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }





    //POST処理2:パスワード変更
    if(!empty($_POST['pass_old'])){
        $pass_old = $_POST['pass_old'];
        $pass_new = $_POST['pass_new'];
        $pass_new_re = $_POST['pass_new_re'];
        //passバリデーション
        validHalf($pass_new, 'pass_new');
        validMaxLen($pass_new, 'pass_new');
        validMinLen($pass_new, 'pass_new');
        validMatch($pass_new, $pass_new_re, 'pass_new_re');
        if($pass_new === $pass_old) $err_msg['pass_new'] = MSG13;

        if(empty($err_msg)){
            debug('バリデーションクリア');
            try {
                $dbh = dbConnect();
                $sql = 'UPDATE users SET password = :pass WHERE id = :id';
                $data = array(':id' => $_SESSION['user_id'], ':pass' => password_hash($pass_new, PASSWORD_DEFAULT));
                $stmt = queryPost($dbh, $sql, $data);

                if($stmt){
                    debug('クエリ成功:パスワードが変更されました。');
                    $_SESSION['msg_success'] = SUC01;
                    header("Location:index.php");
                } else {
                    debug('クエリ失敗');
                    $err_msg['common'] = MSG07;
                }
            } catch (Exception $e) {
                error_log('エラー発生：'.$e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
    if(empty($err_msg[''])){
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE users SET name =:u_name, email = :email, pic = :pic WHERE id = :u_id';
            $data = array(':u_name' => $name, ':email' => $email, ':pic' => $pic, ':u_id' => $dbFormData['id']);
            $stmt = queryPost($dbh, $sql, $data);

            if($stmt){
                debug('クエリ成功です。');
                debug('マイページへ遷移します。');
                $_SESSION['msg_success'] = SUC02;
                header("Location:index.php");
            }
        } catch (Exception $e) {
            debug('エラー発生：'. $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}

debug('プロフィール編集処理終了');
?>

<?php
$siteTitle = 'プロフィール編集';
require('head.php');
?>
<body>

    <!-- 退会確認モーダル-->
        <div class="p-modal__bg js-modal-hide js-modal-body">
            <div class="p-modal__container js-modal-body">
                <div class="p-modal__head">
                    <h3 class="c-title--sm">本当に退会しますか？</h2>
                </div>
                <div class="p-modal__body">
                    <form action="withdraw.php" class="c-btn--sm p-btn-withdraw" method="post">
                        <input type="submit" class="c-btn--sm p-btn--withdraw" name="withdraw" value="はい">
                    </form>
                    <button type="button" class="c-btn--sm p-btn--submit js-modal-hide">いいえ</button>
                </div>
            </div>
        </div>

    <?php require('header.php'); ?>
    <section class="c-container--md">
        <h2 class="c-title">プロフィール編集</h2>
        <div class="c-container__body">
            <form class="c-form p-form--large"  method="post" enctype="multipart/form-data">

                <div class="p-form--profedit">
                <div class="p-form--profedit__head">
                    <lavel for="pic"class="p-label--profedit__img js-area-drop">
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                        <input type="file" name="pic" class="js-input-pic p-img-drop__input" >
                        <img src="<?php echo getFormData('pic'); ?>" class="c-img p-img-drop__img" alt="ユーザー画像">
                        <i class="fas fa-user-circle fa-3x"></i>
                    </label>
                </div>



                <div class="p-form--profedit__body">
                <div class="p-err__msg--common">
                    <span class="err__msg"><?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></span>
                </div>

                <span class="p-msg__err"><?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?></span>
                <input type="text" class="c-input p-input" name="name" placeholder="ユーザー名" value="<?php echo getFormData('name'); ?>">

                <span class="p-msg__err"><?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?></span>
                <input type="email" class="c-input p-input" name="email" placeholder="Email" value="<?php echo getFormData('email'); ?>">


                <!-- セキュリティの観点からpassはプリセットしない。-->
                <span class="c-msg__err"><?php if(!empty($err_msg['pass_old'])) echo getErrMsg('pass_old'); ?></span>
                <input type="password" class="c-input p-input" name="pass_old" placeholder="旧パスワード" value="<?php if(!empty($_POST['pass_new_re'])) echo $_POST['pass_old']; ?>">

                <span class="c-msg__err"><?php if(!empty($err_msg['pass_new'])) echo getErrMsg('pass_old'); ?></span>
                <input type="password" class="c-input p-input" name="pass_new" placeholder="新パスワード" value="<?php if(!empty($_POST['pass_new_re'])) echo $_POST['pass_new']; ?>">

                <span class="c-msg__err"><?php if(!empty($err_msg['pass_new_re'])) echo getErrMsg('pass_old'); ?></span>
                <input type="password" class="c-input p-input" name="pass_new_re" placeholder="新パスワード(再入力)" value="<?php if(!empty($_POST['pass_new_re'])) echo $_POST['pass_new_re']; ?>">

                <div class="p-form--profedit__bottom">
                    <!-- 退会モーダルボタン -->
                    <input type="button" class="js-modal-show c-btn--sm p-btn--withdraw" value="退会">
                    <input type="submit" class="c-btn--sm p-btn--prof-edit" value="変更">
                </div>

            </form>
        </div>
        </div>
    </section>





    <!-- フッター -->
    <?php require('footer.php'); ?>
