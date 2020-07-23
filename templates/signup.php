  <main>
    <?=$menu; ?>
    <form class="form container <?=isset($errors) ? "form--invalid" : ""; ?>" action="signup.php" autocomplete="off" method="POST" enctype="multipart/form-data">
      <h2>Регистрация нового аккаунта</h2>
      <div class="form__item <?=isset($errors['email']) ? "form__item--invalid" : ""; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?=htmlspecialchars(getPostVal('email')); ?>">
        <span class="form__error"><?=$errors['email']; ?></span>
      </div>
      <div class="form__item <?=isset($errors['password']) ? "form__item--invalid" : ""; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?=htmlspecialchars(getPostVal('password')); ?>">
        <span class="form__error"><?=$errors['password']; ?></span>
      </div>
      <div class="form__item <?=isset($errors['name']) ? "form__item--invalid" : ""; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?=htmlspecialchars(getPostVal('name')); ?>">
        <span class="form__error"><?=$errors['name']; ?></span>
      </div>
      <div class="form__item <?=isset($errors['contacts']) ? "form__item--invalid" : ""; ?>">
        <label for="contacts">Контактные данные <sup>*</sup></label>
        <textarea id="contacts" name="contacts" placeholder="Напишите как с вами связаться"><?=htmlspecialchars(getPostVal('contacts')); ?></textarea>
        <span class="form__error"><?=$errors['contacts']; ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
  </main>
