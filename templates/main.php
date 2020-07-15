  <main class="container">
    <section class="promo">
      <h2 class="promo__title">Нужен стафф для катки?</h2>
      <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
      <ul class="promo__list">
      <?php foreach ($categories as $cat): ?>
        <li class="promo__item promo__item--<?=$cat['code']; ?>">
          <a class="promo__link" href="pages/<?=$cat['id']; ?>"><?=$cat['category']; ?></a>
        </li>
      <?php endforeach; ?>
      </ul>
    </section>
    <section class="lots">
      <div class="lots__header">
        <h2>Открытые лоты</h2>
      </div>
      <ul class="lots__list">
      <?php foreach ($items as $item): ?>
        <li class="lots__item lot">
          <div class="lot__image">
            <img src="<?=$item['image_link']; ?>" width="350" height="260" alt="">
          </div>
          <div class="lot__info">
            <span class="lot__category"><?=$item['category']; ?></span>
            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$item['id']; ?>"><?=$item['lot_name']; ?></a></h3>
            <div class="lot__state">
              <div class="lot__rate">
              <?php if (isset($item['rate'])): ?>
                <span class="lot__amount"><?=$item['count'] . ' ' . get_noun_plural_form($item['count'], 'ставка', 'ставки', 'ставок'); ?></span>
                <span class="lot__cost"><?=formatSum($item['rate']); ?></span>                
              <?php else: ?>
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?=formatSum($item['start_price']); ?></span>
              <?php endif; ?>
              </div>
              <div class="lot__timer timer <?=getDateRange($item['final_date'])[0] == '00' ? "timer--finishing" : ""; ?>">
                <?=implode(":", getDateRange($item['final_date'])); ?>
              </div>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
      </ul>
    </section>
  </main>
