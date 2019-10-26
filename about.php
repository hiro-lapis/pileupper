<?php
require('function.php');
$siteTitle = 'aboutページ';
require('head.php');
?>
<body>
    <!-- ヘッダー -->
    <?php require('header.php'); ?>
    <main id="main">
        <!-- アバウト-->
        <section class="c-container--md">
            <div class="c-container__top">
                <h2 class="c-title">About pile upper!</h2>
            </div>
            <div class="c-container__body u-line-height-1h">
                <p>pile upperは筋トレ関連の画像投稿,チャットができるアプリです。</p>
                <p>この人、イイ筋肉してる!と思ったアスリートやアツくなれるマンガからサプリまで、何でも投稿してくださると嬉しいです。</p>
                <p>かくいう私hiroも、2日に1回は筋トレをするほどの筋トレ好き。</p>
                <p>自分の筋トレコレクションを投稿していきたいと思います！</p>
                <ul class="p-item-list__about">
                    <li>機能概要</li>
                    <li>アカウント登録・編集・退会</li>
                    <li>レコードの登録・編集</li>
                    <li>メンバーが投稿したレコードへのコメント</li>
                    <li>レコード詳細閲覧</li>
                    <li>レコード投稿者へメッセージを送る</li>
                </ul>
                <p>
                    会員でない方も、メニューの「覗いてみる」からレコード一覧・詳細・コメントを見ることができます。<br>
                    興味のある方は、ぜひ見ていってください！
                </p>
            </div>
        </section>
    </main>
    <!-- フッター -->
    <?php
    require('footer.php');
    ?>
