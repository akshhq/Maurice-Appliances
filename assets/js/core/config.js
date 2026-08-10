/* Shared client-side configuration and feature detection. */

export const CONFIG = {
  reduceMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
  finePointer:  window.matchMedia('(hover: hover) and (pointer: fine)').matches,
  isTouch:      window.matchMedia('(hover: none), (pointer: coarse)').matches,
  lowPower:     (navigator.hardwareConcurrency || 8) <= 4,
  smallScreen:  window.innerWidth < 720,
  breakpoints:  { sm: 560, md: 720, lg: 900, xl: 1080, xxl: 1180 },
};

/** True when heavy canvas/animation work should be skipped. */
export function shouldReduceEffects() {
  return CONFIG.reduceMotion || CONFIG.lowPower || CONFIG.smallScreen;
}
