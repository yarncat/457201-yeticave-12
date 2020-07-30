  <main>
    <?=$menu; ?>
    <section class="lot-item container">
      <h2><?=$lot['lot_name']; ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="../<?=$lot['image_link']; ?>" width="730" height="548" alt="">
          </div>
          <p class="lot-item__category">Категория: <span><?=$lot['category']; ?></span></p>
          <p class="lot-item__description"><?=$lot['lot_info']; ?></p>
        </div>
        <div class="lot-item__right">
          <div class="lot-item__state">
		  <?php if ($lot['final_date'] < $dateNow): ?>
			<div class="timer timer--end">Торги окончены.</div>
              <p class="lot-item__description"><?=isset($lot['winner']) ? "Поздравляем победителя!" : "Увы! Этот лот никого не заинтересовал"; ?></p>
          <?php else: ?>
            <div class="lot-item__timer timer <?=getDateRange($lot['final_date'])[0] == '00' ? "timer--finishing" : ""; ?>">
              <?=implode(":", getDateRange($lot['final_date'])); ?>
            </div>
          <?php endif; ?>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount"><?=isset($lot['rate']) ? "Текущая цена" : "Стартовая цена"; ?></span>
                <span class="lot-item__cost"><?=isset($lot['rate']) ? formatSum($lot['rate']) : formatSum($lot['start_price']); ?></span>
              </div>
              <div class="lot-item__min-cost">
                <?php if ($lot['final_date'] > $dateNow):?> Мин. ставка <span><?=isset($lot['rate']) ? (formatSum($nextRate) . ' p') : (formatSum($newRate) . ' p'); ?></span><?php endif; ?>
              </div>
            </div>
			<?php if(isset($_SESSION['user']) && ($_SESSION['user']['id'] !== $lot['author']) && ($_SESSION['user']['id'] !== $lastRateUser) && ($lot['final_date'] > $dateNow)): ?>
			<form class="lot-item__form" action="lot.php?id=<?= $lot['id']; ?>" method="POST" autocomplete="off">
              <p class="lot-item__form-item form__item <?=isset($errors) ? "form__item--invalid" : ""; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?=isset($lot['rate']) ? $nextRate : $newRate; ?>">
                <span class="form__error"><?=$errors['cost']; ?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
			<?php endif; ?>
          </div>
		  <div class="history">
            <h3>История ставок (<span><?=$countRatesOnLot; ?></span>)</h3>
            <table class="history__list">
            <?php foreach ($ratesOnLot as $rate): ?>
              <tr class="history__item">
                <td class="history__name"><?=$rate['user_name']; ?></td>
                <td class="history__price"><?=formatSum($rate['rate']) . ' p'; ?></td>
                <td class="history__time"><?=getDifferenceTime($rate['date_rate']); ?></td>
              </tr>
            <?php endforeach; ?>
            </table>
          </div>
        </div>
      </div>
    </section>
  </main>
