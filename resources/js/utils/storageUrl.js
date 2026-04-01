/**
 * Returns the public URL for a file in public/media/ (do not use /storage for media).
 * @param {string|null|undefined} path - Relative path e.g. "products/foo.jpg" or legacy "storage/products/foo.jpg"
 * @returns {string|null} - e.g. "/media/products/foo.jpg" or null if path is empty
 */
export function storageUrl(path) {
  if (path == null || typeof path !== 'string') return null;
  const normalized = path
    .replace(/^\/?(storage\/|public\/|media\/)?/i, '')
    .replace(/\\/g, '/')
    .trim();
  return normalized ? `/media/${normalized}` : null;
}

/**
 * Alias for storageUrl – all media lives under /media/.
 */
export function mediaUrl(path) {
  return storageUrl(path);
}
