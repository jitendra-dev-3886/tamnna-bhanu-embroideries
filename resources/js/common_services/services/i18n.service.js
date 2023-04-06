const i18nService = {
  defaultLanguage: 'en',
  /**
   * Keep the active language in the localStorage
   * @param lang
   */
  setActiveLanguage(lang) {
    localStorage.setItem('language', lang);
  },

  /**
   * Get the current active language
   * @returns {string | string}
   */
  getActiveLanguage() {
    return localStorage.getItem('language') || this.defaultLanguage;
  },
};

export default i18nService;
