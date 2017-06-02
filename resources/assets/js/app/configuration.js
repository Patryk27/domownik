/**
 * Application configuration.
 * Each configuration value is loaded by the backend and set in the layout.
 */

module.exports = (function() {
  var config = {

    /**
     * Application language code (pl, en, gb, etc.)
     * @type {String}
     */
    locale: '',

  };

  return {

    /**
     * @param {String} locale
     * @returns {module.exports}
     */
    setLocale: function(locale) {
      config.locale = locale;
      return this;
    },

    /**
     * @returns {String}
     */
    getLocale: function() {
      return config.locale;
    },

  };
})();