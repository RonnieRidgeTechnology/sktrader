import { onMounted, onUnmounted, ref } from 'vue';

/**
 * Add a class when the element enters the viewport (for scroll-triggered reveal).
 * @param {Object} options - { once?: boolean, rootMargin?: string, threshold?: number, className?: string }
 * @returns {import('vue').Ref<HTMLElement|null>} ref to attach to the element
 */
export function useScrollReveal(options = {}) {
  const { once = true, rootMargin = '0px 0px -8% 0px', threshold = 0, className = 'revealed' } = options;
  const elRef = ref(null);
  let observer = null;

  onMounted(() => {
    if (!elRef.value || typeof IntersectionObserver === 'undefined') return;
    observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          entry.target.classList.add(className);
          if (once && observer) {
            observer.unobserve(entry.target);
          }
        });
      },
      { rootMargin, threshold }
    );
    observer.observe(elRef.value);
  });

  onUnmounted(() => {
    if (observer && elRef.value) {
      observer.disconnect();
    }
  });

  return elRef;
}
