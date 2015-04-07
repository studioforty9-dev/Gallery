/**
 * Studioforty9 Gallery
 *
 * @category  Studioforty9
 * @package   Studioforty9_Gallery
 * @author    StudioForty9 <info@studioforty9.com>
 * @copyright 2015 StudioForty9 (http://www.studioforty9.com)
 * @license   https://github.com/studioforty9/gallery/blob/master/LICENCE BSD
 * @version   1.0.0
 * @link      https://github.com/studioforty9/gallery
 */

/**
 * GridSorter Admin for sorting gallery albums in the admin panel.
 *
 * @category   Studioforty9
 * @package    Studioforty9_Gallery
 * @subpackage JS
 */
var GridSorter = Class.create({
  initialize: function(grid) {
    this.grid = $(grid);
  },
  getData: function() {
    var rows = this.grid.select('tbody tr');
    var data = new Hash();
    rows.each(function(el) {
      var entityId = Element.select(el, 'td input[type="checkbox"]')[0];
      var position = Element.select(el, 'td input[type="text"][name="position"]')[0];

      data.set(
        parseInt(entityId.readAttribute('value'), 10),
        parseInt(position.getValue(), 10)
      );
    });

    varienGlobalEvents.fireEvent("studioforty9.gallery.grid.sorter.serialize", {
      'data': data
    });

    return data;
  },
  save: function(url, method) {
    if (!url) {
      throw new Error('Unable to sort positions. No URL set!');
    }
    var data = this.getData();
    return new Ajax.Request(url, {
      method: method || 'post',
      parameters: data,
      onSuccess: function(transport) {
        var response = transport.responseText ? transport.responseText.evalJSON() : false;
        if (!response.error) {
          varienGlobalEvents.fireEvent("studioforty9.gallery.grid.sorter.update.success", {
            "error": response ? response.error : false,
            "message": response ? response.message : 'No message',
            "data": data.toObject()
          });
        }
      },
      onFailure: function(transport) {
        var response = transport.responseText ? transport.responseText.evalJSON() : false;
        varienGlobalEvents.fireEvent("studioforty9.gallery.grid.sorter.update.failure", {
          "error": response ? response.error : true,
          "message": response ? response.message : 'No message',
          "data": data.toObject()
        });
      }
    });
  }
});
