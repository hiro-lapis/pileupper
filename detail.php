<?php
require('function.php');
require('auth.php');
debug('レコード詳細ページ');
debugLogStart();
debug('詳細処理開始');

$record_id = (!empty($_GET['t_id'])) ? $_GET['t_id'] : '';

//POST['msg']ならメッセージ処理
if(!empty($_POST['msg'])){
    require('msg.php');
}

$viewData = getToDoOne($record_id);
$dbMsgData = getMsg($record_id);
$dbRecordUser = getUser($viewData['user_id']);

if(empty($viewData)){
    error_log('エラー発生:不正な値が入りました。');
    header("Location:top.php");
} else {
    debug('取得したDBデータ:'.print_r($viewData, true));
}

//レコード投稿者のID＝閲覧ユーザーIDならeditフラグ
$edit_flg = ($viewData['user_id'] === $_SESSION['user_id']) ? true : false;

debug('---画面表示処理終了---')
?>

<?php
$siteTitle = 'レコード詳細';
require('head.php');
?>

<body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>
    <p class="js-show-msg c-msg-slide" class=""><?php echo getSessionFlash('msg_success'); ?></p>

    <main id="main">
        <!-- レコード詳細 -->
        <section class="c-container-md">
            <h2 class="c-title">レコード詳細</h2>


            <div class="p-panel-wrap">
                <div class="c-panel p-panel"><!-- extend on p-panel-->
                    <!-- レコード画像 -->
                    <div class="p-panel__head">
                        <img class="c-img p-img--record" src="<?php echo showImg(sanitize($viewData['pic1'])); ?>"  alt="投稿画像" >
                    </div>
                    <!-- 投稿ユーザー -->
                    <div class="p-panel__body">
                        <div class="p-panel__left">
                            <div class="p-img--circle p-panel__left--img">
                                <?php if(!empty($dbRecordUser['pic'])){ ?>
                                    <img class="c-img p-img--user" src="<?php echo showImg(sanitize($dbRecordUser['pic'])); ?>"alt="ユーザー画像">
                                <?php } else { ?>
                                    <img class="c-img p-img--user" src="img/no-user.jpeg" alt="ユーザー画像">
                                <?php } ?>
                                <p class="p-panel__left--name">ユーザー：<?php echo sanitize($dbRecordUser['user']); ?></p>
                            </div>
                        </div>


                        <div class="p-panel__right">
                            <ul>
                                <li>鍛えた筋肉：<?php echo sanitize($viewData['category']); ?></li>
                                <li>コメント：<?php echo sanitize($viewData['comment']); ?></li>
                                <li><?php echo sanitize($viewData['create_date']); ?></li>
                                <li>いいね数:
                                    <span class="js-click-nice c-btn__nice <?php if(isNice($_SESSION['user_id'], $viewData['id'])){ echo 'is-active'; } ?>" data-recordid= "<?php echo sanitize($viewData['id']); ?>">
                                        <i class="fas fa fa-dumbbell" aria-hidden= "true" ></i>
                                        <span class="p-nice-num"><?php echo getNiceNum($viewData['id']); ?></span>
                                    </span>
                                </li>
                            </ul>
                        </div>

                    </div><!-- ./p-panel__body -->

                    <div class="p-panel__footer">
                        <a href="index.php<?php echo appendGetParam(array('t_id'));?>">&lt;レコード一覧に戻る</a>
                        <?php if($edit_flg){  ?>
                            <a href="makerecord.php?t_id=<?php echo $record_id;?>">レコードを編集する&gt;</a>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </section><!-- ./container -->
    </main>



     <aside id="aside">
        <!-- メッセージボックス -->
        <section class="c-container">
        <div class="p-message__area--outer">


            <div class="p-message__area--inner <?php if(empty($dbMsgData)) echo 'u-flex--center' ?>">
        <!-- メッセージがある場合-->
        <?php if(!empty($dbMsgData)){ foreach ($dbMsgData as $key => $val): ?>
            <div class="p-message <?php if($val['from_user'] === $_SESSION['user_id']) echo 'p-message--from-master';?>">
                        <div class="p-message__header">
                            <div class="p-message__header--icon p-img--circle">
                            <?php if(!empty($val['user_image'])){ ?>
                                <img class="c-img p-img--user" src="<?php echo sanitize($val['user_image']); ?>"alt="ユーザー画像">
                            <?php  } else { ?>
                                <img class="c-img p-img--user" src="img/no-user.jpeg" alt="ユーザー画像">
                            <?php } ?>
                            </div>
                            <p class="p-message__header-name p-user_name"><?php echo sanitize($val['name']); ?></p>
                        </div>

                        <div class="p-message__body <?php if($val['from_user'] === $_SESSION['user_id']) echo 'p-balloon--right'; ?>">
                            <p class="p-message__text"><?php echo sanitize($val['msg']); ?></p>
                            <time><?php echo sanitize($val['send_date']); ?></time>
                        </div>
            </div>

                <!-- メッセージがない場合-->
                <?php endforeach; } else { ?>
                        <p class="u-text--center">メッセージはまだありません。</p>
                <?php  } ?>
        </div><!-- ./p-message__area-innrer-->


            <!-- メッセージフォーム -->
                <form class="c-form p-form--message" method="post">
                    <div class="c-input p-form--message__body">
                        <label for="msg">メッセージ
                            <span class="js-err-msg c-msg__err"><?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?></span>
                            <span class="c-msg__err"><?php if(!empty($err_msg['msg'])) echo $err_msg['msg']; ?></span>
                        </label>
                        <textarea name="msg" class="js-count c-form__text-area p-text-area" rows="5" cols="40" placeholder="140字以内" required></textarea>
                        <div class="p-text--counter"><span class="js-count-view">0</span>/140</div>
                    </div>
                    <div class="c-input p-form--message__footer">
                        <button type="submit" class="js-btn-submit c-btn--md p-btn--msg"><i class="far fa-paper-plane u-disp--contents"></i> 送信</button>
                    </div>
                </form>


            </div>
            </section>
        </aside>

        <!-- フッター -->
        <?php require('footer.php'); ?>
