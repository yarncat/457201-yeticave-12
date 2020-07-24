  <main>
    <?=$menu; ?>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <?php if (!empty($myRates)): ?>
      <table class="rates__list">
      <?php foreach ($myRates as $item): ?>
        <tr class="rates__item <?php if($item['winner'] === $_SESSION['user']['id']): ?>rates__item--win <?php elseif ($item['final_date'] < $dateNow): ?>rates__item--end<?php endif; ?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$item['image_link']; ?>" width="54" height="40" alt="<?=$item['category']; ?>">
            </div>
            <div>
              <h3 class="rates__title"><a href="lot.php?id=<?=$item['id']; ?>"><?=$item['lot_name']; ?></a></h3>
              <?php if($item['winner'] === $_SESSION['user']['id']) : ?><p><?=$item['user_contacts']; ?></p><?php endif; ?>
            </div>
          </td>
          <td class="rates__category">
            <?=$item['category']; ?>
          </td>
          <td class="rates__timer">
          <?php if ($item['winner'] === $_SESSION['user']['id']): ?>
            <div class="timer timer--win">Ставка выиграла</div>
          <?php elseif ($item['final_date'] < $dateNow): ?>
            <div class="timer timer--end">Торги окончены</div>
          <?php else: ?>
            <div class="lot__timer timer <?=getDateRange($item['final_date'])[0] === '00' ? "timer--finishing" : ""; ?>">
              <?=implode(":", getDateRange($item['final_date'])); ?>
            </div>
          <?php endif; ?>
          </td>
          <td class="rates__price">
            <?=$item['rate']; ?>
          </td>
          <td class="rates__time">
            <?=getDifferenceTime($item['date_rate']); ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </table>
      <?php else: ?>
      <p><b><?=$notFound; ?></b></p>
      <?php endif; ?>
    </section>
  </main>
