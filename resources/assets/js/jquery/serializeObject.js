(function($) {
  /**
   * Thanks to: http://stackoverflow.com/questions/11376184/jquery-serializearray-key-value-pairs
   * @returns {Object.<string, string>}
   */
  $.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();

    $.each(a, function() {
      if (o[this.name] !== undefined) {
        if (!o[this.name].push) {
          o[this.name] = [o[this.name]];
        }
        o[this.name].push(this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });

    return o;
  };
})(jQuery);