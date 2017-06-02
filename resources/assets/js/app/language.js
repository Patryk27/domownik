module.exports = (function() {

  /**
   * Message list.
   */
  var messages = {};

  /**
   * Currently selected language.
   * Automatically initialized.
   * @type {String}
   */
  var locale = null;

  /**
   * Returns given message translation.
   * @param {String} key
   * @returns {String}
   */
  window.__ = function(key) {
    return module.exports.getMessage(key);
  }

  return {

    /**
     * @param {{}} optMessages
     * @returns {exports}
     */
    initialize: function(optMessages) {
      messages = optMessages;

      $(function() {
        locale = App.Configuration.getLocale();
      });

      return this;
    },

    /**
     * @param {String} key
     * @returns {String}
     */
    getMessage: function(key) {
      var split = key.split(':');

      var moduleName = split[0],
          itemPath = split[1].split('.');

      var message = messages[moduleName][locale];

      for (var i = 0; i < itemPath.length; ++i) {
        message = message[itemPath[i]];
      }

      return message;
    },

  };
})();