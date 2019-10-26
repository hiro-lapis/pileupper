<?php
require('function.php');
require('auth.php');
debugLogStart();
debug('レコード登録ページ');

$record_id = (!empty($_GET['t_id'])) ? $_GET['t_id'] : '';
//DBからレコードデータを取得
$dbFormData = (!empty($record_id)) ? getToDo($_SESSION['user_id'], $record_id) : '';
//新規登録画面か編集画面か判定 false=新規登録画面
$edit_flg = (empty($dbFormData)) ? false :true ;
//カテゴリデータを取得
$dbCategoryData = getCategory();

debug('todoのID：'.$record_id);
debug('フォーム用データ：'.print_r($dbFormData, true));
debug('カテゴリーデータ：'.print_r($dbCategoryData, true));

//パラメータ改ざんチェック クエリパラメータが改ざんされてデータを取得できない場合indexへ遷移
if(!empty($record_id) && empty($dbFormData)){
    debug('GETパラメータのtodoIDが違います。マイページへ遷移します。');
    header("Location:index.php");
}

if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST, true));
    debug('FILE情報：'.print_r($_FILES, true));

    $name = $_POST['name'];
    $category = $_POST['category_id'];
    $comment = $_POST['comment'];
    //サーバー内へ画像を格納,DBにはパス
    $pic1 = ( !empty($_FILES['pic1']['name']) ) ? uploadImg($_FILES['pic1'],'pic1') : '';
    // 画像をPOSTしてなくて、かつ既にDBに画像が登録されている場合、DBからとってきた画像のパスを代入
    $pic1 = ( empty($pic1) && !empty($dbFormData['pic1']) ) ? $dbFormData['pic1'] : $pic1;


    //新規登録
    if(empty($dbFormData)){
        //nameバリデーション
        validRequired($name,'name');
        validMaxLen($name, 'name');
        //categoryバリデーション
        validSelect($category, 'category_id');
        //commentバリデーション
        validMaxMbLen($comment, 'comment', 140);
    } else {
        //レコード編集
        if($dbFormData['name'] !== $name){
            //nameバリデーション
            validRequired($name ,'name');
            validMaxMbLen($name, 'name', 20);
        }
        //categoryバリデーション
        if($dbFormData['category_id'] !== $category){
            validSelect($category, 'category_id');
        }
        if($dbFormData['comment'] !== $comment){
            validMaxLen($comment, 'comment', 255);
        }
    }

    if(empty($err_msg)){
        debug('バリデーションOK');
        try {
            $dbh = dbConnect();


            if($edit_flg){
                debug('レコード更新');
                $sql = 'UPDATE todo SET name = :name, category_id = :category, comment = :comment, pic1 = :pic1, update_date = :update_at WHERE user_id = :u_id AND id = :todo_id ';
                $data = array(':name' => $name, ':category' => $category, ':comment' => $comment, ':pic1' => $pic1, ':update_at' => date('Y-m-d H:i:s'), ':u_id' => $_SESSION['user_id'], ':todo_id' => $record_id);
            } else {

                debug('新規登録');
                $sql = 'INSERT INTO todo (name, category_id, comment, pic1,  user_id, create_date) VALUES (:name, :category,  :comment, :pic1,  :u_id, :create)';
                $data = array(':name' => $name, ':category' => $category, ':comment' => $comment,  ':pic1' => $pic1,':u_id' => $_SESSION['user_id'], ':create' => date('Y-m-d H:i:s'));
            }
            $stmt = queryPost($dbh, $sql, $data);

            //クエリ成功の場合,トグル表示するメッセージを設定
            if($stmt){
                debug('レコードのcategoryID:'.$category);
                switch ($category) {
                    case 1:
                    $_SESSION['msg_success']= MUS01;
                    debug('表示するメッセージ：'.MUS01);
                    break;
                    case 2:
                    $_SESSION['msg_success']= MUS02;
                    debug('表示するメッセージ：'.MUS02);
                    break;
                    case 3://上腕二頭筋
                    $_SESSION['msg_success']= MUS03;
                    debug('表示するメッセージ：'.MUS03);
                    break;
                    case 4:
                    $_SESSION['msg_success']= MUS04;
                    debug('表示するメッセージ：'.MUS04);
                    break;
                    case 5:
                    $_SESSION['msg_success']= MUS05;
                    debug('表示するメッセージ：'.MUS05);
                    break;
                    case 6:
                    $_SESSION['msg_success']= MUS06;
                    debug('表示するメッセージ：'.MUS06);
                    break;
                    case 7:
                    $_SESSION['msg_success']= MUS07;
                    debug('表示するメッセージ：'.MUS07);
                    break;
                    case 8:
                    $_SESSION['msg_success']= MUS08;
                    debug('表示するメッセージ：'.MUS08);
                    break;
                    case 9:
                    $_SESSION['msg_success']= MUS9;
                    debug('表示するメッセージ：'.MUS09);
                    break;
                    case 10:
                    $_SESSION['msg_success']= MUS10;
                    debug('表示するメッセージ：'.MUS10);
                    break;
                }
            }

            debug('マイページへ遷移します。');
            header("Location:index.php");

        } catch (Exception $e) {
            error_log('エラー発生:'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }

    }
}
debug('画面表示処理完了');
?>
<?php
$siteTitle = (!$edit_flg) ?  'todo登録' :'todo編集';
require('head.php');
?>

<body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- メインコンテンツ -->
    <main id="main">
        <section class="c-container--md">
            <h2 class="c-title"><?php echo (!$edit_flg) ? 'レコード登録' : 'レコード編集'; ?></h2>
            <div class="c-container__body">

                <div class="p-form--record">
                    <!-- 投稿フォーム -->
                    <form method="post" class="c-form p-recording-form" enctype="multipart/form-data">

                        <div class="p-form--record__head">
                            <!-- プレビュー付きドロップエリア -->
                            <label for="pic1" class="p-img-drop"><span class="c-msg__err"><?php if(!empty($err_msg['pic1'])) echo $err_msg['pic1']; ?></span></label>

                            <div class="js-area-drop p-img-drop-area">
                                <i class="fas fa-camera fa-3x p-img-drop__icon"></i>
                                <input type="hidden" name ="MAX_FILE_SIZE" value="3145728">
                                <input type="file" name="pic1" class="js-input-pic p-img-drop__input"><!-- 画像のインプットを受け取るのはこっち -->
                                <img src="<?php echo getFormData('pic1'); ?>" class="js-prev-img p-img-drop__img" alt="投稿画像プレビュー">
                            </div>
                        </div>



                        <div class="p-form--record__body">
                            <label for="name" class="c-label">レコード名
                                <span class="c-msg__err">
                                    <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                                    <?php if(!empty($err_msg['name'])) echo $err_msg['name']; ?>
                                </span>
                            </label>
                            <input type="text" class="c-input p-input" name="name" value="<?php echo getFormData('name'); ?>">

                            <!-- カテゴリ -->
                            <label class="c-label" for="category_id">見どころポイント
                                <?php if(!empty($err_msg['category_id'])) echo $err_msg['category_id']; ?>
                            </label>
                            <select name="category_id" class="c-input p-input">
                                <option value="0" <?php if(getFormData('category_id', true) == 0){echo 'selected'; } ?> >選択してください。</option>
                                <?php foreach ($dbCategoryData as $key => $val) {  ?>

                                    <option value="<?php echo $val['id'] ?>" <?php if(getFormData('category_id') == $val['id']) { echo 'selected'; } ?> >
                                        <?php echo $val['name']; ?>
                                    </option>

                                <?php } ?>
                            </select>

                            <!-- コメント -->
                            <label for="comment" class="c-label">コメント</label>
                            <span class="c-msg__err"><?php if(!empty($err_msg['comment'])) echo $err_msg['comment']; ?></span>
                            <div class="p-form--record__bottom">
                                <textarea name="message" class="js-count c-form__text-area p-text-area--record" rows="5" cols="40" placeholder="140字以内" required><?php echo (!empty(getFormData('comment'))) ?  getFormData('comment'): ''; ?></textarea>
                                <div class="p-text--counter"><span class="js-count-view">0</span>/140</div></textarea>
                            </div>
                        </div>

                        <button class="c-btn--wide c-btn--center p-btn--submit" type="submit"><i class="fas fa-angle-double-right fa-lg"></i>投稿</button>





                    </div><!-- ./cpntainer__body -->
                </form>
            </div>

        </section>
    </main>
    <!-- フッター -->
    <?php require('footer.php'); ?>
