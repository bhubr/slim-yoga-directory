"use strict";
function ajaxSearch( _inputId, _listId, _searchUrl, _searchOnStart, _selectCallback, _state, _options ) {
  (function( inputId, listId, searchUrl, searchOnStart, selectCallback, state, options ) {
    var input = $('#' + inputId);
    var list = $('#' + listId);
    var multi = !!options && !!options.multi;
    var otherInputs = ( !!options && !!options.otherInputs ) ? options.otherInputs : {};
    var searchTerms = {};
    var selected = [];
    // console.log(inputId, multi ? 'is multi' : 'is not multi', otherInputs);

    var dbgId = 'dbg-' + inputId;
    var dbgInput = $('<input class="debug" id="' + dbgId + '" />').appendTo(input.parent());
    // var qs = input.quicksearch('ul#' + listId + ' li');

    function bindListItemEvents() {
      list.find('li').click( function(e) {
        var target = $(e.target);
        var id = target.data('id');
        selectCallback( target, input, list, state );
        if(multi) {
          if( selected.indexOf(id) === -1 ) {
            selected.push( id );
          }
        }
        else {
          selected = [id];
        }
        console.log( 'selected', id, selected );
        dbgInput.val( selected.join( ',' ) );

        $(window).trigger('ajaxfilter:' + dbgId, [selected]);
        // console.log('TRIGGER', 'ajaxfilter:' + dbgId);
      } );
    }

    function fireAjaxSearch(extraData) {
      // var searchTerm = searchTerm !== undefined ? searchTerm : '';
      console.log('FIRE', $('#' + inputId), $('#' + inputId).attr( 'name' ));
      // var searchTerms = {};
      var searchStrings = [];
      var inputName = input.attr( 'name' );
      extraData = extraData || {};
      console.log('extra', extraData);
      if( !!inputName ) {
        searchTerms[inputName]= input.val();
      }

      for( var s in searchTerms ) {
        searchStrings.push( s + '=' + searchTerms[s] );
      }

      var fields = (!!options && !!(options.fields) ) ?
        '&fields=' + options.fields.join(',') : '';
      $.ajax({
        'url': searchUrl + '?' + searchStrings.join('&') + fields,
        'type': 'GET',
        'dataType': 'json',
        'success': function (data) {
          list.removeClass('hidden');
          list.find('li').remove();
          for (var i in data['items']) {
              var item = data['items'][i];
              var id = item['id'];
              var listItem;
              delete item.id;
              listItem = $('<li data-id="' + id + '">' + item['name'] + '</li>').appendTo(list).css('display', 'block');
              $(listItem).data('attrs', item);
          }
          bindListItemEvents();
          // qs.cache();
        }
      });
    }
    
    for( var _inputId in otherInputs ) {
      var mapTo = otherInputs[_inputId];
      var dbgId = 'dbg-' + _inputId;
      console.log( 'SETUP CHANGE', inputId, '=>', mapTo, ': ', $('#dbg-' + inputId), 'ajaxfilter:' + dbgId );
      // $('#dbg-' + inputId).change(function() {
      //   console.log( 'DETECT CHANGE', inputId, '=>', mapTo, ': ', $(this).val() );
      // });

      var cb = ( function(_mapTo) {
        return function(event, data) { console.log('filter RECV', _inputId, mapTo, data);
          searchTerms[_mapTo] = data;
          fireAjaxSearch();
         };
      } )(mapTo);
      $(window).on('ajaxfilter:' + dbgId, cb
        

        
      );
    }
    input.on('input', fireAjaxSearch);

    if( searchOnStart ) {
      fireAjaxSearch();
    }
  })( _inputId, _listId, _searchUrl, _searchOnStart, _selectCallback, _state, _options );
}