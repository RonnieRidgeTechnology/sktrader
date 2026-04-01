/**
 * Format a date string or Date for display.
 * @param {string|Date|null|undefined} value - ISO date string or Date
 * @param {Intl.DateTimeFormatOptions} [options] - Optional Intl options
 * @returns {string} - Formatted string or '—' if invalid
 */
export function formatDate(value, options = {}) {
  if (value == null) return '—';
  const date = typeof value === 'string' ? new Date(value) : value;
  if (Number.isNaN(date.getTime())) return '—';
  return date.toLocaleString(undefined, {
    dateStyle: 'medium',
    timeStyle: 'short',
    ...options,
  });
}
