<!-- サイドバーカテゴリー-->
<aside class="p-side-bar js-search-menu-target">
  <form class="c-form p-form--search" method="get">


    <label class="p-form--search__head c-label" for="c_id"><p class="p-text">カテゴリー</p>
      <select name="c_id" class="c_input p-input--search">

        <option value="0" <?php if(getFormData('c_id', true) == 0) {echo 'selected'; } ?> >
          選択してください
        </option>

        <?php foreach ($dbCategoryData as $key => $val) { ?>

        <option value="<?php echo $val['id']; ?>" <?php if(getFormData('c_id', true) == $val['id']){ echo 'selected'; } ?> >
          <?php echo $val['name']; ?>
        </option>

        <?php } ?>

      </select>
    </label>


    <!-- サイドバー(ソート) -->
    <label for="sort"><p class="p-text">表示順</p>
        <select name="sort" class="c-input p-input--search">
          <option value="0" <?php if(getFormData('sort', true) == 0 ){echo 'selected'; } ?> > 選択してください </option>
          <option value="1" <?php if(getFormData('sort', true) == 1 ){echo 'selected'; } ?> > 登録が古い順 </option>
          <option value="2" <?php if(getFormData('sort', true) == 2 ){echo 'selected'; } ?> > 登録が新しい順 </option>
        </select>
    </label>

      <button class="c-btn--sm c-btn--center p-btn--search-start" type="submit"><i class="fa fa-search"></i>検索</button>
  </form>
</aside>
