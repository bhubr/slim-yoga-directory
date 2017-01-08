"use strict";
function ajaxSearch( _inputId, _listId, _searchWhat, _searchOnStart, _selectCallback, _state, _options ) {
  (function( inputId, listId, searchWhat, searchOnStart, selectCallback, state, options ) {
    var input = $('#' + inputId);
    var list = $('#' + listId);
    // var qs = input.quicksearch('ul#' + listId + ' li');

    function bindListItemEvents() {
      list.find('li').click( function(e) {
        selectCallback( $(e.target), input, list, state );
      } );
    }

    function fireAjaxSearch() {
      // var searchTerm = searchTerm !== undefined ? searchTerm : '';
      var searchTerm = input.attr( 'name' ) + '=' + input.val();
      var fields = (!!options && !!(options.fields) ) ?
        '&fields=' + options.fields.join(',') : '';
      $.ajax({
        'url': '/search/' + searchWhat + '?' + searchTerm + fields,
        'type': 'GET',
        'dataType': 'json',
        'success': function (data) {
          // console.log(data);
          list.removeClass('hidden');
          list.find('li').remove();
          for (var i in data['items']) {
              var item = data['items'][i];
              var id = item['id'];
              var listItem;
              delete item.id;
              listItem = $('<li data-id="' + id + '">' + item['name'] + '</li>').appendTo(list).css('display', 'block');
              // console.log('<li data-id="' + id + '">' + item['name'] + '</li>', item, list, listItem);
              $(listItem).data('attrs', item);
          }
          bindListItemEvents();
          qs.cache();
        }
      });
    }
      
    input.on('input', fireAjaxSearch);

    if( searchOnStart ) {
      fireAjaxSearch();
    }
  })( _inputId, _listId, _searchWhat, _searchOnStart, _selectCallback, _state, _options );
}