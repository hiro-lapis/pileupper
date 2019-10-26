<?php
require('function.php');

debug('「　ログアウトページ　');
debugLogStart();
// セッションを削除
session_destroy();
debug('ログインページへ遷移');
header("Location:top.php");
