<footer id="footer" class="l-footer">
  ©︎CopyRight hiro AllReserved
</footer>
<?php $login_flg = (!empty($_SESSION['user_id'])) ? true: false; ?>
<script>var login_flg = "<?php echo $login_flg; ?>"</script>
 <!-- niceボタンを押すためのフラグ -->
<script src="./js/app.js"></script>
</body>
</html>
