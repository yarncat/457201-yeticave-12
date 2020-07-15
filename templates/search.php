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
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=htmlspecialchars(trim($_GET['search'])); ?></span>»</h2>
        <?php if (!empty($items)): ?>
        <ul class="lots__list">
        <?php foreach ($items as $item): ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="../<?=$item['image_link']; ?>" width="350" height="260" alt="">
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
        <?php else: ?>
        <p><b><?=$notFound; ?></b></p>
        <?php endif; ?>
      </section>
      <?php if (isset($pagesCount) && $pagesCount > 1): ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="search.php?search=<?=htmlspecialchars($_GET['search']); ?>&page=<?=($currentPage <= 1) ? $currentPage = 1 : $currentPage - 1; ?>">Назад</a></li>
        <?php foreach ($pages as $page): ?>
        <li class="pagination-item <?=($page === $currentPage) ? "pagination__item--active" : ""; ?>"><a href="search.php?search=<?=htmlspecialchars($_GET['search']); ?>&page=<?=$page;?>"><?=$page;?></a></li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next"><a href="search.php?search=<?=htmlspecialchars($_GET['search']); ?>&page=<?=($currentPage >= $pagesCount) ? $currentPage = $pagesCount : $currentPage + 1; ?>">Вперед</a></li>
      </ul>
      <?php endif; ?>
    </div>
  </main>
