import { onMounted, onUnmounted } from 'vue';

/**
 * Injects a JSON-LD script into document.head. Removes it on unmount.
 * @param {() => object|object[]} getSchema - Function or object returning the schema (e.g. Product, FAQPage).
 */
export function useJsonLd(getSchema) {
  let scriptEl = null;

  onMounted(() => {
    const schema = typeof getSchema === 'function' ? getSchema() : getSchema;
    if (!schema) return;
    scriptEl = document.createElement('script');
    scriptEl.type = 'application/ld+json';
    scriptEl.textContent = JSON.stringify(Array.isArray(schema) ? schema : schema);
    document.head.appendChild(scriptEl);
  });

  onUnmounted(() => {
    if (scriptEl && scriptEl.parentNode) {
      scriptEl.parentNode.removeChild(scriptEl);
    }
  });
}
