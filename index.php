<?php
require('function.php');
debug('タスク一覧表示ページ');
debugLogStart();
debug('一覧表示処理開始');

//カテゴリーフラグ
$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';

//GETパラメータからpage情報を取得。false（GETが空）の時は1を代入
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1;
//パラメーのタ値チェック
if(!is_int((int)$currentPageNum)){
    errror_log('エラー発生：指定ページに不正な値が入りました。');
    header("Location:top.php");
}
//niceボタンを押すためのフラグ
$login_flg = !empty($_SESSION['user_id']);
//表示件数
$listSpan = 10;
//表示レコードの先頭(OFFSET)を算出
$currentMinNum = (($currentPageNum - 1) * $listSpan);
//レコードデータ取得
$dbTodoData = getToDoList($currentMinNum, $category, $sort);
//カテゴリーデータを取得
$dbCategoryData = getCategory();
debug('現在のページ：'.$currentPageNum);
debug('画面表示処理終了');
?>
<?php $siteTitle ='インデックス';
require('head.php');
?>
<body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>

    <!-- サイドバー  -->
    <?php require('sidebar.php'); ?>

    <p class="js-show-msg c-msg-slide" class=""><?php echo getSessionFlash('msg_success'); ?></p>

    <main id="main">

        <!-- 検索結果表示 -->
        <section class="c-container--md">
            <div class="p-search-rst">
                <div class="p-search-rst__head">
                    <span class="p-search-num"><?php echo sanitize($dbTodoData['total']); ?>個のレコードが見つかりました。</span>
                </div>
                <div class="p-search-rst__body">
                    <p class="p-search-rst-num">
                        <?php echo (!empty($dbTodoData['data'])) ? $currentMinNum+1 : 0; ?>
                        -<?php echo $currentMinNum+count($dbTodoData['data']); ?>件 /
                        <?php echo sanitize($dbTodoData['total']); ?>件中
                    </p>
                </div>
            </div>
        </section>

        <!-- レコード一覧 -->
        <section class="c-container--md">
            <div class="p-panel-area">
                <?php foreach ($dbTodoData['data'] as $key => $val): ?>


                    <div class="c-panel p-panel">

                        <!-- レコードイメージ(panel__head) -->
                        <a href="detail.php?t_id=<?php echo $val['id'].'&p='.$currentPageNum; ?>" class="c-nav">
                        <div class="p-panel__head--sm">
                                <img class="p-img--record" src="<?php echo showImg(sanitize($val['pic1'])); ?>" alt="<?php echo sanitize($val['name']); ?>">
                        </div>


                        <!-- ユーザーイメージ(panel__bodyレフト)-->
                        <div class="p-panel__body">
                            <div class="p-panel__left">
                                <div class="p-img--circle p-panel__left--img">
                                    <?php if(!empty($val['user_image'])){ ?>
                                        <img class="c-img p-img--user" src="<?php echo sanitize($val['user_image']); ?>"alt="ユーザー画像">
                                        <?php ; } else { ?>
                                            <img class="c-img p-img--user" src="img/no-user.jpeg" alt="ユーザー画像">
                                        <?php } ?>
                                </div>
                                    <p class="p-panel__left--name"><?php echo sanitize($val['user']); ?></p>
                            </div>


                                <div class="p-panel__right">
                                    <!-- ユーザーネーム・カテゴリ・投稿日時 -->
                                    <p><?php echo sanitize($val['category']); ?></p>
                                    <time class="c-panel__time"><?php echo sanitize($val['create_date']); ?></time>
                                    <div class="p-panel__bottom">
                                        <!-- niceボタン -->


                                    </div>
                                </div>
                            </div>

                        </a>
                        </div>
                    <?php endforeach; ?>
                </div><!-- ./panel-area-->
            </section>


            <!-- ページネーション -->
            <div class="c-container--md p-text-link p-pagenation__container">
                <?php pagination($currentPageNum, $dbTodoData['total_page']); ?>
            </div>
        </main>
        <!-- フッター -->
        <?php require('footer.php'); ?>
