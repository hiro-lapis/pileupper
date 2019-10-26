<header class="l-header">
<div class="c-container--header js-float-menu p-bg-sub-color">
<!-- ロゴ -->
<div class="c-site-logo">
  <h2 class="c-site-logo__title">
      <a class="c-site-logo__link" href="<?php  echo (!empty($_SESSION['user_id'])) ? 'index.php': 'top.php'; ?>">Pile Upper</a>
  </h2>
</div>
<!-- ナイトモードボタン -->
<!-- <div class="p-btn- mode-change">
        <i class="is-active fas fa-sun fa-border fa-3x c-btn -circle p-btn -sun js-mode-change" aria-hidden="true"></i>
        <i class="fas fa-moon fa-border fa-3x c-btn -circle p-btn -moon js-mode-change" aria-hidden="true"></i>
</div> -->
<!-- ハンバーガーメニュー -->
  <div class="c-menu-trigger js-toggle-menu js-toggle-sp-menu-target">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <?php if(basename($_SERVER['PHP_SELF']) === 'index.php'){ ?>
  <!-- サーチボタン -->
  <div class="js-toggle-search-menu c-btn--circle p-btn--search">
      <i class="fas fa-search fa-2x  js-mode-change aria-hidden="true""></i>
  </div>
<?php } ?>
  <!--トグルメニュー -->
  <div class="c-nav__menu__bg js-click-close-menu js-toggle-sp-menu-target"></div>
  <div class="c-nav__menu__slide js-toggle-sp-menu-target">
      <?php if(empty($_SESSION['user_id'])){ ?>
    <nav class="c-nav__menu c-nav__menu">
      <ul class="c-menu__item-list">
        <li class="c-menu__item"><a href="top.php">トップ</a></li>
        <li class="c-menu__item"><a href="about.php">アプリの概要</a></li>
        <li class="c-menu__item"><a href="index.php">覗いてみる</a></li>
        <li class="c-menu__item"><a href="signup.php">アカウント作成</a></li>
      </ul>
    </nav>
<?php } else {  ?>
    <nav class="c-nav__menu c-nav__menu">
        <ul class="c-menu__item-list">
            <li class="c-menu__item"><a href="makerecord.php">投稿する</a></li>
            <li class="c-menu__item"><a href="index.php">記録を見る</a></li>
            <li class="c-menu__item"><a href="profedit.php">アカウント編集</a></li>
            <li class="c-menu__item"><a href="logout.php">ログアウト</a></li>
        </ul>
    </nav>
  </div>
  <?php } ?>
  <span class="js-float-target"></span>
</div>
<div class="c-container__bottom">
    <p class="js-show-msg p-slide-msg is-hide" class=""><?php echo getSessionFlash('msg_success'); ?></p>
</div>
</header>
