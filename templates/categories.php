  <main>
    <nav class="nav">
      <ul class="nav__list container">
      <?php foreach ($categories as $category): ?>
        <li class="nav__item <?=($currentCategory === $category['category']) ? "nav__item--current" : ""; ?>">
          <a href="categories.php?id=<?=$category['id']; ?>"><?=$category['category']; ?></a>
        </li>
      <?php endforeach; ?>
      </ul>
    </nav>
    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории «<span><?=$currentCategory; ?></span>»</h2>
        <?=$lots; ?>
      </section>
      <?php if (isset($pagesCount) && $pagesCount > 1): ?>
      <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a href="categories.php?id=<?=htmlspecialchars($_GET['id']); ?>&page=<?=($currentPage <= 1) ? 1 : $currentPage - 1; ?>">Назад</a></li>
        <?php foreach ($pages as $page): ?>
        <li class="pagination-item <?=($page === $currentPage) ? "pagination__item--active" : ""; ?>"><a href="categories.php?id=<?=htmlspecialchars($_GET['id']); ?>&page=<?=$page;?>"><?=$page;?></a></li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next"><a href="categories.php?id=<?=htmlspecialchars($_GET['id']); ?>&page=<?=($currentPage >= $pagesCount) ? $pagesCount : $currentPage + 1; ?>">Вперед</a></li>
      </ul>
      <?php endif; ?>
    </div>
  </main>
