module.exports = (function() {

  /**
   * Message list.
   * @type {{}}
   */
  var messages = {};

  /**
   * Currently selected locale.
   * @type {?String}
   */
  var locale = null;

  /**
   * Returns given translation.
   * Example keys:
   *  'Finances:something.someone' or
   *  ':something.someone', when referring to global namespace.
   * @param {String} key
   * @returns {String}
   */
  window.__ = function(key) {
    return module.exports.getMessage(key);
  }

  //noinspection JSUnusedGlobalSymbols
  return {

    /**
     * @param {String} paramLocale
     * @returns {exports}
     */
    initialize: function(paramLocale) {
      messages = window.AppLocalizationMessages;
      locale = paramLocale;

      return this;
    },

    /**
     * @param {String} key
     * @returns {String}
     */
    getMessage: function(key) {
      var split = key.split(':');

      var moduleName = split[0],
          keyPath = split[1].split('.');

      try {
        keyPath = [moduleName, locale].concat(keyPath);

        var keys = messages;

        for (var i = 0; i < keyPath.length; ++i) {
          var keyItem = keyPath[i];

          if (!keys.hasOwnProperty(keyItem)) {
            throw 'translation-not-found';
          }

          keys = keys[keyItem];
        }

        return keys;
      } catch (ex) {
        if (ex === 'translation-not-found') {
          /**
           * Mock the Laravel behaviour - that is: return referenced language key
           * except throwing an exception. Makes it easier for debugging.
           */

          console.log('Translation key could not have been found: ' + key);
          return key;
        }

        throw ex;
      }
    },

  };
})();