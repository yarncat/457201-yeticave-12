    <main>
        <nav class="nav">
            <ul class="nav__list container">
      <?php foreach ($categories as $cat): ?>
        <li class="nav__item">
          <a href="pages/<?=$cat['id']; ?>"><?=$cat['category']; ?></a>
        </li>
      <?php endforeach; ?>
            </ul>
        </nav>
        <section class="lot-item container">
            <h2>403</h2>
            <p>Для добавления лота необходимо пройти регистрацию или войти в аккаунт.</p>
        </section>
    </main>
