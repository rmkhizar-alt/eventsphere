/* ---------- Subtle Sound System ---------- */
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

let audioContext = null;
let soundEnabled = false;
let initialized = false;

function $(sel, doc = document) { return doc.querySelector(sel); }

function initSoundSystem() {
  if (initialized || prefersReducedMotion.matches) return;
  initialized = true;

  const getCtx = () => {
    if (!audioContext) {
      try { audioContext = new (window.AudioContext || window.webkitAudioContext)(); }
      catch (e) { return null; }
    }
    return audioContext;
  };

  // Save toggle state to localStorage
  const saved = localStorage.getItem('eventSphereSounds');
  soundEnabled = saved === 'false' ? false : true;

  // Toggle function
  window.toggleEventSphereSounds = function() {
    soundEnabled = !soundEnabled;
    localStorage.setItem('eventSphereSounds', soundEnabled);
    updateSoundToggleUI();
  };

  function updateSoundToggleUI() {
    const btn = $('#sound-toggle');
    if (!btn) return;
    btn.setAttribute('aria-pressed', String(soundEnabled));
    btn.innerHTML = `<i data-lucide="${soundEnabled ? 'volume-2' : 'volume-off'}" class="w-3.5 h-3.5"></i>`;
    lucide.createIcons();
  }

  updateSoundToggleUI();

  // Generate a brief oscillator burst (pop/click sound)
  function playTone(freq, duration, type = 'sine', volume = 0.1) {
    if (!soundEnabled) return;
    const ctx = getCtx();
    if (!ctx) return;
    if (ctx.state === 'suspended') ctx.resume();

    const osc = ctx.createOscillator();
    const gain = ctx.createGain();

    osc.type = type;
    osc.frequency.value = freq;

    gain.gain.setValueAtTime(volume, ctx.currentTime);
    gain.gain.linearRampToValueAtTime(0, ctx.currentTime + duration);

    osc.connect(gain);
    gain.connect(ctx.destination);

    osc.start(ctx.currentTime);
    osc.stop(ctx.currentTime + duration);
  }

  // Short "pop" click sound
  window.playPop = function() { playTone(1200, 0.08, 'sine', 0.08); };

  // Soft hover click
  window.playHover = function() { playTone(800, 0.05, 'triangle', 0.04); };

  // Gentle success affirmation
  window.playSuccess = function() {
    if (!soundEnabled) return;
    const ctx = getCtx();
    if (!ctx) return;
    if (ctx.state === 'suspended') ctx.resume();

    const osc1 = ctx.createOscillator();
    const osc2 = ctx.createOscillator();
    const gain = ctx.createGain();

    osc1.type = 'sine'; osc1.frequency.value = 600;
    osc2.type = 'sine'; osc2.frequency.value = 800;
    gain.gain.setValueAtTime(0.06, ctx.currentTime);
    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);

    osc1.connect(gain); osc2.connect(gain);
    gain.connect(ctx.destination);

    osc1.start(ctx.currentTime); osc1.stop(ctx.currentTime + 0.15);
    osc2.start(ctx.currentTime + 0.05); osc2.stop(ctx.currentTime + 0.3);
    gain.connect(ctx.destination);
  };

  // Subtle error alert
  window.playError = function() { playTone(200, 0.15, 'sine', 0.05); };

  // Card hover sound
  document.addEventListener('mouseover', (e) => {
    if (!soundEnabled || prefersReducedMotion.matches) return;
    const card = e.target.closest('.event-card, .media-card, .card-natural');
    if (card) playHover();
  });

  // Card click sound
  document.addEventListener('mousedown', (e) => {
    if (!soundEnabled || prefersReducedMotion.matches) return;
    const card = e.target.closest('.event-card, .media-card');
    if (card) playPop();
  });

  // Navigation link click
  document.addEventListener('click', (e) => {
    if (!soundEnabled || prefersReducedMotion.matches) return;
    const link = e.target.closest('a.nav-link');
    if (link) playPop();
  });

  // Button hover
  document.addEventListener('mouseover', (e) => {
    if (!soundEnabled || prefersReducedMotion.matches) return;
    const btn = e.target.closest('button');
    if (btn && !btn.closest('.toast')) playHover();
  });

  // Form submit
  document.addEventListener('submit', (e) => {
    if (!soundEnabled || prefersReducedMotion.matches) return;
    const form = e.target.closest('form');
    if (form) playSuccess();
  });

  // Toast notification appear/dismiss
  const origShowToast = window.showToast;
  if (origShowToast) {
    // Wrap the toast function if it exists
  }

  // Direct toast hook: listen for toast creation events
  const toastRoot = $('#toast-root');
  if (toastRoot) {
    const toastObserver = new MutationObserver((mutations) => {
      if (!soundEnabled || prefersReducedMotion.matches) return;
      // Find newly added toasts
      const newToasts = [...toastRoot.children].filter(n => n.classList && n.classList.contains('toast'));
      if (newToasts.length) {
        playSuccess();
        // Reset after one toast to avoid spamming
        setTimeout(() => { toastObserver.disconnect(); }, 5000);
      }
    });
    toastObserver.observe(toastRoot, { childList: true, subtree: true });
  }
}

// Initialize on first user interaction
window.addEventListener('load', () => {
  // Don't auto-init - wait for user gesture
});

document.addEventListener('click', () => {
  if (!initialized) initSoundSystem();
  document.removeEventListener('click', arguments.callee once);
}, { once: true });