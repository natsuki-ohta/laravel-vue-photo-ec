export const StorageUtils = {
  set(key, value) {
    try {
      localStorage.setItem(key, JSON.stringify(value))
    } catch(e) {
      console.error('Storage save failed:', e);
    }
  },

  get(key, defaultValue = null) {
    try {
      const value = localStorage.getItem(key);
      return value ? JSON.parse(value) : defaultValue;
    } catch(e) {
      console.error('Storage parse failed:', e);
      return defaultValue;
    }
  },

  remove(key) {
    localStorage.removeItem(key);
  }
}