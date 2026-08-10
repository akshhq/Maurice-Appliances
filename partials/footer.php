<?php
/** PARTIAL: site footer — newsletter, link columns, contact, legal */
$co   = get_company();
$cats = get_categories();
?>
<footer class="footer">
  <div class="footer__glow" aria-hidden="true"></div>
  <div class="wrap">
    <div class="footer__top">
      <div class="footer__brand">
        <a class="footer__logo" href="<?= e(url('index.php')) ?>">
          <span>maurice</span><i>&reg;</i>
        </a>
        <p class="footer__tag"><?= e($co['tagline'] ?? '') ?></p>
        <p class="footer__mission">Complete home solutions, crafted for everyday living. ISI &amp; ISO-certified appliances made in India since <?= (int)($co['established'] ?? 2010) ?>.</p>
        <div class="footer__certs">
          <span class="badge badge--red">BIS · ISI Certified</span>
          <span class="badge badge--ember">ISO 9001:2015</span>
        </div>
      </div>

      <div class="footer__news">
        <p class="footer__col-title">Stay in the loop</p>
        <p class="muted footer__news-sub">New launches, dealer offers and product updates — occasionally, never spam.</p>
        <form class="footer__form" id="newsletterForm" method="post" action="<?= e(url('api/v1/newsletter.php')) ?>" novalidate>
          <?= csrf_field() ?>
          <div class="footer__field">
            <input type="email" name="email" placeholder="Your email address" aria-label="Email address" required>
            <button type="submit" class="btn btn--sm" data-cursor="Subscribe">
              <span>Subscribe</span>
              <svg class="arrow" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
          </div>
          <p class="footer__form-msg" id="newsletterMsg" role="status" aria-live="polite"></p>
        </form>
      </div>
    </div>

    <div class="footer__cols">
      <div class="footer__col">
        <p class="footer__col-title">Products</p>
        <ul>
          <?php foreach (array_slice($cats, 0, 6) as $c): ?>
          <li><a href="<?= e(url(category_url($c['id']))) ?>"><?= e($c['name']) ?></a></li>
          <?php endforeach; ?>
          <li><a href="<?= e(url('products.php')) ?>" class="footer__all">All products &rarr;</a></li>
        </ul>
      </div>
      <div class="footer__col">
        <p class="footer__col-title">More products</p>
        <ul>
          <?php foreach (array_slice($cats, 6) as $c): ?>
          <li><a href="<?= e(url(category_url($c['id']))) ?>"><?= e($c['name']) ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="footer__col">
        <p class="footer__col-title">Company</p>
        <ul>
          <li><a href="<?= e(url('pages/about.php')) ?>">About Maurice</a></li>
          <li><a href="<?= e(url('pages/journey.php')) ?>">Our Journey</a></li>
          <li><a href="<?= e(url('pages/vision.php')) ?>">Vision &amp; Mission</a></li>
          <li><a href="<?= e(url('pages/manufacturing.php')) ?>">Manufacturing</a></li>
          <li><a href="<?= e(url('pages/careers.php')) ?>">Careers</a></li>
          <li><a href="<?= e(url('pages/media.php')) ?>">Media</a></li>
        </ul>
      </div>
      <div class="footer__col">
        <p class="footer__col-title">Support</p>
        <ul>
          <li><a href="<?= e(url('pages/service.php')) ?>">After-Sales Service</a></li>
          <li><a href="<?= e(url('pages/warranty.php')) ?>">Warranty</a></li>
          <li><a href="<?= e(url('pages/faq.php')) ?>">FAQ</a></li>
          <li><a href="<?= e(url('pages/downloads.php')) ?>">Downloads</a></li>
          <li><a href="<?= e(url('pages/become-dealer.php')) ?>">Become a Dealer</a></li>
        </ul>
      </div>
      <div class="footer__col footer__col--contact">
        <p class="footer__col-title">Customer Support</p>
        <ul class="footer__contact">
          <li>
            <span class="footer__ci">Phone</span>
            <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['phones'][0] ?? '')) ?>"><?= e($co['phones'][0] ?? '') ?></a>
          </li>
          <?php if (!empty($co['tollFree'])): ?>
          <li>
            <span class="footer__ci">Toll-Free</span>
            <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $co['tollFree'])) ?>"><?= e($co['tollFree']) ?></a>
          </li>
          <?php endif; ?>
          <li>
            <span class="footer__ci">Email</span>
            <a href="mailto:<?= e($co['email'] ?? '') ?>"><?= e($co['email'] ?? '') ?></a>
          </li>
          <li>
            <span class="footer__ci">Address</span>
            <span><?= e($co['address'] ?? '') ?></span>
          </li>
        </ul>
      </div>
    </div>

    <div class="footer__bottom">
      <p>&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. All rights reserved.</p>
      <div class="footer__legal">
        <a href="<?= e(url('pages/privacy.php')) ?>">Privacy Policy</a>
        <a href="<?= e(url('pages/terms.php')) ?>">Terms</a>
        <a href="<?= e(url('pages/contact.php')) ?>">Contact</a>
      </div>
    </div>
  </div>
</footer>
