  <main>
  <?=$menu; ?>
    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?=htmlspecialchars(trim($_GET['search'])); ?></span>»</h2>
        <?=$lots; ?>
      </section>
      <?php if (isset($pagesCount) && $pagesCount > 1): ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="search.php?search=<?=htmlspecialchars($_GET['search']); ?>&page=<?=($currentPage <= 1) ? 1 : $currentPage - 1; ?>">Назад</a></li>
        <?php foreach ($pages as $page): ?>
        <li class="pagination-item <?=($page === $currentPage) ? "pagination__item--active" : ""; ?>"><a href="search.php?search=<?=htmlspecialchars($_GET['search']); ?>&page=<?=$page;?>"><?=$page;?></a></li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next"><a href="search.php?search=<?=htmlspecialchars($_GET['search']); ?>&page=<?=($currentPage >= $pagesCount) ? $pagesCount : $currentPage + 1; ?>">Вперед</a></li>
      </ul>
      <?php endif; ?>
    </div>
  </main>
