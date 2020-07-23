  <main>
  <?=$menu; ?>
    <form class="form form--add-lot container <?=isset($errors) ? "form--invalid" : ""; ?>" action="add.php" method="POST" enctype="multipart/form-data">
      <h2>Добавление лота</h2>
      <div class="form__container-two">
        <div class="form__item <?=isset($errors['lot_name']) ? "form__item--invalid" : ""; ?>">
          <label for="lot-name">Наименование <sup>*</sup></label>
          <input id="lot-name" type="text" name="lot_name" placeholder="Введите наименование лота" value="<?=htmlspecialchars(getPostVal('lot_name')); ?>">
          <span class="form__error"><?=$errors['lot_name']; ?></span>
        </div>
        <div class="form__item <?=isset($errors['category']) ? "form__item--invalid" : ""; ?>">
          <label for="category">Категория <sup>*</sup></label>
          <select id="category" name="category">
            <option disabled selected>Выберите категорию</option>
            <?php foreach ($categories as $category): ?>
            <option value="<?=$category['id'] ?>" <?=$category['id'] === getPostVal('category') ? "selected" : ""; ?>><?=htmlspecialchars($category['category']); ?></option>
            <?php endforeach; ?>
          </select>
          <span class="form__error"><?=$errors['category']; ?></span>
        </div>
      </div>
      <div class="form__item form__item--wide <?=isset($errors['message']) ? "form__item--invalid" : ""; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Введите описание лота"><?=htmlspecialchars(getPostVal('message')); ?></textarea>	
        <span class="form__error"><?=$errors['message']; ?></span>
      </div>
      <div class="form__item form__item--file <?=isset($errors['image']) ? "form__item--invalid" : ""; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
          <input class="visually-hidden" type="file" id="lot-img" name="image">
          <label for="lot-img">
            Добавить
          </label>
        </div>
        <span class="form__error"><?=$errors['image']; ?></span>
      </div>
      <div class="form__container-three">
        <div class="form__item form__item--small <?=isset($errors['lot_rate']) ? "form__item--invalid" : ""; ?>">
          <label for="lot-rate">Начальная цена <sup>*</sup></label>
          <input id="lot-rate" type="text" name="lot_rate" placeholder="0" value="<?=htmlspecialchars(ltrim(getPostVal('lot_rate'), ' , 0')); ?>">
          <span class="form__error"><?=$errors['lot_rate']; ?></span> 
        </div>
        <div class="form__item form__item--small <?=isset($errors['lot_step']) ? "form__item--invalid" : ""; ?>">
          <label for="lot-step">Шаг ставки <sup>*</sup></label>
          <input id="lot-step" type="text" name="lot_step" placeholder="0" value="<?=htmlspecialchars(ltrim(getPostVal('lot_step'), ' , 0')); ?>">
          <span class="form__error"><?=$errors['lot_step']; ?></span> 
        </div>
        <div class="form__item <?=isset($errors['lot_date']) ? "form__item--invalid" : ""; ?>">
          <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
          <input class="form__input-date" id="lot-date" type="text" name="lot_date" placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?=htmlspecialchars(getPostVal('lot_date')); ?>">
          <span class="form__error"><?=$errors['lot_date']; ?></span>
        </div>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Добавить лот</button>
    </form>
  </main>
