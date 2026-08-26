import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Scroll-triggered reveals.
 * [data-scroll-reveal] wale sections viewport mein aane par reveal hote hain.
 * Optional attribute: data-scroll-reveal-delay="0.2" (seconds).
 */
export function setupScrollEffects() {
    if (prefersReducedMotion()) return;

    const targets = document.querySelectorAll('[data-scroll-reveal]');
    if (!targets.length) return;

    targets.forEach((el) => {
        const delay = parseFloat(el.dataset.scrollRevealDelay) || 0;

        gsap.fromTo(
            el,
            { opacity: 0, y: 32 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                delay,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: el,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                },
            },
        );
    });
}
