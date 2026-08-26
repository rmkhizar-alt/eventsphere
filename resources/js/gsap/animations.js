import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Page-load intro animations.
 * [data-animate] elements hero mein fade/slide-in hote hain (staggered).
 */
export function runIntroAnimations() {
    if (prefersReducedMotion()) {
        gsap.set('[data-animate]', { opacity: 1, y: 0 });
        return;
    }

    const targets = document.querySelectorAll('[data-animate]');
    if (!targets.length) return;

    // Hero elements CSS se hidden hain ([data-animate] { opacity: 0 }),
    // GSAP unhe stagger ke sath reveal karta hai.
    gsap.fromTo(
        targets,
        { opacity: 0, y: 24 },
        {
            opacity: 1,
            y: 0,
            duration: 0.7,
            ease: 'power2.out',
            stagger: 0.12,
            delay: 0.15,
            clearProps: 'transform',
        },
    );
}

/**
 * Button micro-interactions: hover par subtle scale + glow.
 */
export function setupButtonEffects() {
    if (prefersReducedMotion()) return;

    document.querySelectorAll('[data-animate-hover]').forEach((btn) => {
        btn.addEventListener('mouseenter', () =>
            gsap.to(btn, { scale: 1.03, duration: 0.2, ease: 'power2.out' }),
        );
        btn.addEventListener('mouseleave', () =>
            gsap.to(btn, { scale: 1, duration: 0.25, ease: 'power2.out' }),
        );
        btn.addEventListener('mousedown', () =>
            gsap.to(btn, { scale: 0.98, duration: 0.1 }),
        );
        btn.addEventListener('mouseup', () =>
            gsap.to(btn, { scale: 1.03, duration: 0.15 }),
        );
    });
}

/**
 * Page transitions: internal links par smooth fade-out, phir navigate.
 */
export function setupPageTransitions() {
    if (prefersReducedMotion()) return;

    // Page load par body fade-in
    gsap.fromTo(
        document.body,
        { opacity: 0 },
        { opacity: 1, duration: 0.35, ease: 'power1.out' },
    );

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (!link) return;

        const href = link.getAttribute('href');
        const isInternal =
            href &&
            !href.startsWith('#') &&
            !href.startsWith('mailto:') &&
            link.origin === window.location.origin &&
            !event.metaKey &&
            !event.ctrlKey &&
            !event.shiftKey &&
            link.target !== '_blank';

        if (!isInternal) return;

        event.preventDefault();
        gsap.to(document.body, {
            opacity: 0,
            duration: 0.25,
            ease: 'power1.in',
            onComplete: () => window.location.assign(href),
        });
    });
}
