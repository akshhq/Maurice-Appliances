/**
 * MAURICE APPLIANCES — Homepage Controller (Pure Vanilla JS)
 * Single Unified Hero Showcase, Dynamic Credential Stats,
 * Amazon-style category bento grid, interactive finder & B2B express form.
 */

import { ALL_PRODUCTS, CATEGORIES, COMPANY } from "../data/products.js?v=3.0";
import { initProductFinder } from "../modules/product-finder.js?v=3.0";
import { initReveals } from "../core/scroll.js?v=3.0";

const reduceMotion = window.matchMedia(
  "(prefers-reduced-motion: reduce)",
).matches;
let initialized = false;

export function initHomePage() {
  if (initialized) return;
  initialized = true;

  // Initialize hero banner slider first so auto-sliding starts immediately
  try {
    initHeroBannerSlider();
  } catch (err) {
    console.error("Hero banner slider init error:", err);
  }

  initDynamicHeroStats();
  countUp();
  emberField();
  initProductFinder();
  initB2BExpressForm();
  initReveals();
}

/* ---- 7-Second Looping Hero Banner Slider ---- */
function initHeroBannerSlider() {
  const slider = document.getElementById("heroBannerSlider");
  const track = document.getElementById("heroSliderTrack");
  const dotsWrap = document.getElementById("heroSliderDots");
  const prevBtn = document.getElementById("heroSliderPrev");
  const nextBtn = document.getElementById("heroSliderNext");

  if (!slider || !track) return;

  const slides = [
    ...track.querySelectorAll(".hero-banner__slide, .hero__slide"),
  ];
  const dots = dotsWrap
    ? [...dotsWrap.querySelectorAll(".hero-banner__dot, .hero__slider-dot")]
    : [];
  const totalSlides = slides.length;
  if (totalSlides <= 1) return;

  let currentIndex = 0;
  let autoTimer = null;
  const slideInterval = 7000; // 7 seconds loop

  function updateSliderVisuals(instant = false) {
    track.style.transition = instant
      ? "none"
      : "transform 0.75s cubic-bezier(0.25, 1, 0.5, 1)";
    track.style.transform = `translateX(-${currentIndex * 100}%)`;

    dots.forEach((dot, idx) => {
      const isActive = idx === currentIndex;
      dot.classList.toggle("is-active", isActive);
      dot.setAttribute("aria-selected", isActive ? "true" : "false");

      // Restart CSS animation for active dot fill
      const fill = dot.querySelector(
        ".hero-banner__dot-fill, .hero__slider-dot-fill",
      );
      if (fill) {
        fill.style.animation = "none";
        if (isActive) {
          void fill.offsetWidth; // Force reflow
          fill.style.animation = `heroBannerProgress ${slideInterval}ms linear forwards`;
        }
      }
    });
  }

  function goToSlide(index, userInitiated = false) {
    if (index >= totalSlides) {
      currentIndex = 0;
    } else if (index < 0) {
      currentIndex = totalSlides - 1;
    } else {
      currentIndex = index;
    }

    updateSliderVisuals();
    if (userInitiated) {
      resetTimer();
    }
  }

  function nextSlide(userInitiated = false) {
    goToSlide(currentIndex + 1, userInitiated);
  }

  function prevSlide(userInitiated = false) {
    goToSlide(currentIndex - 1, userInitiated);
  }

  function startTimer() {
    stopTimer();
    if (document.visibilityState === "visible") {
      autoTimer = setInterval(() => {
        nextSlide(false);
      }, slideInterval);
    }
  }

  function stopTimer() {
    if (autoTimer) {
      clearInterval(autoTimer);
      autoTimer = null;
    }
  }

  function resetTimer() {
    stopTimer();
    startTimer();
  }

  // Button navigation (manual interaction resets the 7s countdown)
  prevBtn?.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    prevSlide(true);
  });

  nextBtn?.addEventListener("click", (e) => {
    e.preventDefault();
    e.stopPropagation();
    nextSlide(true);
  });

  // Dots navigation
  dots.forEach((dot, idx) => {
    dot.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      goToSlide(idx, true);
    });
  });

  // Pause slider when hovered on desktop
  slider.addEventListener("mouseenter", () => {
    slider.classList.add("is-paused");
    stopTimer();
  });
  slider.addEventListener("mouseleave", () => {
    slider.classList.remove("is-paused");
    startTimer();
  });

  // Touch Swipe Support (Mobile & Tablet)
  let touchStartX = 0;
  let touchStartY = 0;
  let touchStartTime = 0;

  slider.addEventListener(
    "touchstart",
    (e) => {
      touchStartX = e.touches[0].clientX;
      touchStartY = e.touches[0].clientY;
      touchStartTime = Date.now();
      stopTimer();
    },
    { passive: true },
  );

  slider.addEventListener(
    "touchend",
    (e) => {
      const touchEndX = e.changedTouches[0].clientX;
      const touchEndY = e.changedTouches[0].clientY;
      const diffX = touchEndX - touchStartX;
      const diffY = touchEndY - touchStartY;
      const elapsedTime = Date.now() - touchStartTime;

      // Horizontal swipe threshold (> 40px and more horizontal than vertical)
      if (
        Math.abs(diffX) > 40 &&
        Math.abs(diffX) > Math.abs(diffY) &&
        elapsedTime < 600
      ) {
        if (diffX < 0) {
          nextSlide(true);
        } else {
          prevSlide(true);
        }
      } else {
        startTimer();
      }
    },
    { passive: true },
  );

  // Keyboard navigation
  window.addEventListener("keydown", (e) => {
    if (
      document.activeElement &&
      ["INPUT", "TEXTAREA", "SELECT"].includes(document.activeElement.tagName)
    ) {
      return;
    }
    if (e.key === "ArrowLeft") {
      prevSlide(true);
    } else if (e.key === "ArrowRight") {
      nextSlide(true);
    }
  });

  // Page visibility awareness: pause when tab hidden, resume when tab active
  document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "hidden") {
      stopTimer();
    } else {
      startTimer();
    }
  });

  // Expose global methods for testing/debugging
  window.__mauriceSlider = {
    goToSlide,
    nextSlide,
    prevSlide,
    startTimer,
    stopTimer,
  };

  // Start immediately
  updateSliderVisuals(true);
  startTimer();
}

/* ---- 1. Dynamic Hero Stats ---- */
function initDynamicHeroStats() {
  const prodEl = document.getElementById("heroStatProducts");
  const catEl = document.getElementById("heroStatCats");
  const yearsEl = document.getElementById("heroStatYears");

  const totalProducts = ALL_PRODUCTS.length || 118;
  const totalCats = CATEGORIES.length || 11;
  const currentYear = new Date().getFullYear();
  const yearsBuilt = Math.max(16, currentYear - (COMPANY.established || 2010));

  if (prodEl) {
    prodEl.setAttribute("data-count", String(totalProducts));
    prodEl.textContent = String(totalProducts);
  }
  if (catEl) {
    catEl.setAttribute("data-count", String(totalCats));
    catEl.textContent = String(totalCats);
  }
  if (yearsEl) {
    yearsEl.setAttribute("data-count", String(yearsBuilt));
    yearsEl.textContent = String(yearsBuilt);
  }
}

/* ---- 2. Count Up Stats ---- */
function countUp() {
  const els = document.querySelectorAll("[data-count]");
  if (!els.length) return;
  if (reduceMotion || !("IntersectionObserver" in window)) {
    els.forEach((el) => {
      el.textContent = el.dataset.count + (el.dataset.suffix || "");
    });
    return;
  }
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const end = parseInt(el.dataset.count, 10) || 0;
        const suffix = el.dataset.suffix || "";
        const dur = 1400;
        const start = performance.now();
        function step(now) {
          const p = Math.min(1, (now - start) / dur);
          const eased = 1 - Math.pow(1 - p, 3);
          el.textContent = Math.round(eased * end) + suffix;
          if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
        io.unobserve(el);
      });
    },
    { threshold: 0.5 },
  );
  els.forEach((el) => io.observe(el));
}

/* ---- 3. Ember Particle Field (2D Canvas) ---- */
function emberField() {
  const canvas = document.getElementById("emberCanvas");
  if (!canvas || reduceMotion) return;
  if (
    window.innerWidth < 720 ||
    (navigator.hardwareConcurrency && navigator.hardwareConcurrency <= 4)
  )
    return;

  const ctx = canvas.getContext("2d");
  let w, h, dpr, particles, raf;

  function size() {
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    w = canvas.clientWidth;
    h = canvas.clientHeight;
    canvas.width = w * dpr;
    canvas.height = h * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function seed() {
    const count = Math.min(36, Math.floor(w / 32));
    particles = Array.from({ length: count }, () => ({
      x: Math.random() * w,
      y: h + Math.random() * h,
      r: Math.random() * 2 + 0.6,
      vy: Math.random() * 0.45 + 0.15,
      vx: (Math.random() - 0.5) * 0.2,
      a: Math.random() * 0.35 + 0.1,
      hue: Math.random() > 0.5 ? "255,106,61" : "224,30,38",
    }));
  }

  function frame() {
    ctx.clearRect(0, 0, w, h);
    for (const p of particles) {
      p.y -= p.vy;
      p.x += p.vx;
      if (p.y < -10) {
        p.y = h + 10;
        p.x = Math.random() * w;
      }
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${p.hue},${p.a})`;
      ctx.shadowBlur = 6;
      ctx.shadowColor = `rgba(${p.hue},${p.a})`;
      ctx.fill();
    }
    ctx.shadowBlur = 0;
    raf = requestAnimationFrame(frame);
  }

  size();
  seed();
  frame();
  window.addEventListener(
    "resize",
    () => {
      cancelAnimationFrame(raf);
      size();
      seed();
      frame();
    },
    { passive: true },
  );
}

/* ---- 4. B2B / Dealer Express Onboarding Form ---- */
function initB2BExpressForm() {
  const form = document.getElementById("expressDealerForm");
  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const name = form.querySelector('[name="name"]')?.value.trim();
    const city = form.querySelector('[name="city"]')?.value.trim();
    const phone = form.querySelector('[name="phone"]')?.value.trim();

    if (!name || !city || !phone) {
      if (window.showToast)
        window.showToast(
          "Please fill all 3 fields for express callback.",
          "warning",
        );
      return;
    }

    const btn = form.querySelector('button[type="submit"]');
    const originalLabel = btn ? btn.innerHTML : "";
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = "Submitting...";
    }

    const formData = new FormData(form);
    formData.append("formType", "express_dealer_callback");

    try {
      const response = await fetch("./api/submit-form.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(result.message || "Submission failed");
      }

      if (window.showToast) {
        window.showToast(
          "Express application received! Our regional distributor manager will call you within 2 hours.",
          "success",
        );
      } else {
        alert(
          "Express application received! Our regional distributor manager will call you within 2 hours.",
        );
      }
      form.reset();
    } catch (error) {
      console.error("Express dealer callback error:", error);
      if (window.showToast) {
        window.showToast(
          "Unable to submit the application. Please try again.",
          "error",
        );
      }
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalLabel;
      }
    }
  });
}

// Auto-run if loaded
if (document.readyState !== "loading") {
  initHomePage();
} else {
  document.addEventListener("DOMContentLoaded", initHomePage);
}
document.addEventListener("maurice:ready", initHomePage);
