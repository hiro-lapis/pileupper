$(function(){

//sp用ハンバーガーメニュー
  $('.js-toggle-menu').on('click', function () {
      $('.js-toggle-sp-menu-target').toggleClass('active');
  });

  // メニューをクリックしたらメニューを閉じる
  $('.js-click-close-menu').on('click', function () {
      $('.js-toggle-sp-menu-target').toggleClass('active');
  });

  // sp用検索メニュ
  $('.js-toggle-search-menu').on('click', function () {
      $('.js-search-menu-target').toggleClass('is-active');
  });

  //フロートヘッダー
  const $headerHeight = $('.js-float-target').offset().top;
  $(window).on('scroll', function(){
      $('.js-float-menu').toggleClass('is-float', $(this).scrollTop() > $headerHeight)
      });


  //フッターを最下部に固定
  const $ftr = $('#footer');
  $(window).on('load resize', function(){
      if(window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
          $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
      };
  });


  //toggleメッセージ
  const $jsShowMsg = $('.js-show-msg');
  const msg = $jsShowMsg.text();
  if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
    $jsShowMsg.slideToggle('slow');
    setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
  };


  //未ログイン時警告メッセージ
  $jsShowMsg.on('change', function(){
    if($jsShowMsg.text() === 'ログインユーザーでないと利用できません'){
        $jsShowMsg.slideToggle('slow');
        setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
      };
    });


  //画像ライブプレビュー
  const $dropArea = $('.js-area-drop');
  const $fileInput = $('.js-input-pic');

  $dropArea.on('dragover', function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', '3px #ccc dashed');
  });
  $dropArea.on('dragleave', function(e){
    e.stopPropagation();
    e.preventDefault();
    $(this).css('border', 'none');
  });

  $fileInput.on('change', function(e){
    $dropArea.css('border', 'none');
    var file = this.files[0],
        $img = $(this).siblings('.js-prev-img'),
        fileReader = new FileReader();
        //fileReaderについて https://www.html5rocks.com/ja/tutorials/file/dndfiles/

    fileReader.onload = function(event) {
      //読込完了後,src(参照先)にデータをセット
      $img.attr('src', event.target.result).show();
    };
    //画像を文字列形式のデータURLに変換
    fileReader.readAsDataURL(file);
  });

  //テキストエリアカウント
  const $countUp = $('.js-count'),
      $countView = $('.js-count-view');

      $countUp.on('keyup', function(e) {
        let comment = $(this).val();
        $countView.text(comment.length);
        if(comment.length > 140){
          $('.js-btn-submit').prop("disabled", true);
          $('.js-err-msg').text('140字以内で入力してください');
        } else {
          $('.js-btn-submit').prop("disabled", false);
          $('.js-err-msg').text('');
        };
      });

  //モーダル
  const $modalOn = $('.js-modal-show');
  let $modalOff = $('.js-modal-hide');
  const $modalBody = $('.js-modal-body');

  $modalOn.on('click', function(e){
      e.stopPropagation();      
      console.log('modal');// TODO: 本番削除
    $modalBody.fadeIn();
  });
  $modalOff.on('click', function(){
      console.log('off');// TODO: 本番削除
     $modalBody.fadeOut();
  });


  //NiceボタンのAjax機能
  let $nice = $('.js-click-nice') || null,
      niceRecordId;

  $nice.on('click' ,function(){
    let $this = $(this);
    niceRecordId = $this.data('recordid') || null;
    // TODO:リファクタ後削除 console.log(niceRecordId);

    if(niceRecordId !== undefined && niceRecordId !== null){

      if(login_flg){
        //login_flgはfooter.php内で設定
        $.ajax({
          url: 'ajaxnice.php',
          type: 'POST',
          data: { recordId : niceRecordId }
        }).done(function( data ){
          //いいね総数を書き換え
          $this.children('span').text(data);
          //$thisのclassをtoggleclassで付け外しおする
          $this.toggleClass('is-active');
          //成功をコンソール表示 TODO: logは削除
          console.log('Ajax Success');
        }).fail(function( msg ){
          //失敗した時の処理  logはTODO:削除
          console.log('Ajax Error');
          $jsShowMsg.text('ログインユーザーでないと利用できません');
        });
      };
    };
  });

  //ナイトモード切り替え
  const $ModeBtn = $('.js-mode-change');
  $ModeBtn.on('click', function(){
      console.log('mode');
      let $this = $(this);
      $this.removeClass('is-active')
      .siblings('.fas').addClass('is-active');
  })



});
