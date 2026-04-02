import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/** BCP 47 locale for Intl.NumberFormat by ISO 4217 code (legacy ZMW supported). */
export function localeForIsoCurrency(code) {
  const c = String(code || 'USD').toUpperCase();
  if (c === 'PKR') return 'en-PK';
  if (c === 'ZMW') return 'en-ZM';
  return 'en-US';
}

/**
 * Storefront currency from admin settings (USD or PKR) + formatters.
 * @returns {{ currency: import('vue').ComputedRef<string>, formatMoney: (amount: number|string|null|undefined, currencyOverride?: string|null) => string, localeForIsoCurrency: typeof localeForIsoCurrency }}
 */
export function useStoreCurrency() {
  const page = usePage();
  const currency = computed(() => {
    const c = page.props.zuaaz?.store_currency;
    return c === 'PKR' ? 'PKR' : 'USD';
  });

  function formatMoney(amount, currencyOverride = null) {
    const cur = currencyOverride
      ? String(currencyOverride).toUpperCase()
      : currency.value;
    const n = Number(amount);
    if (!Number.isFinite(n)) return '—';
    const loc = localeForIsoCurrency(cur);
    try {
      return new Intl.NumberFormat(loc, {
        style: 'currency',
        currency: cur,
        minimumFractionDigits: 2,
      }).format(n);
    } catch {
      return `${cur} ${n.toFixed(2)}`;
    }
  }

  return { currency, formatMoney, localeForIsoCurrency };
}
