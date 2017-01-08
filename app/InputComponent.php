<?php
/**
 * Slim Twig Component.
 * 
 * @link https://github.com/bhubr/... for the canonical source repository
 *
 * @copyright Copyright (c) 2017 Benoit Hubert
 */

class InputComponent implements ComponentInterface {

    public function __construct( $properties = [] ) {
        $this->properties = $properties;
    }

    public function renderHtml() {
        extract($this->properties);
        $output = '';
        $output .=  "<h4>Input component #$inputId</h4>";
        $output .= "<input id=\"$inputId\" class=\"form-control\" placeholder=\"$placeholder\" name=\"name\" type=\"name\" value=\"\">";
        $output .= "<ul id=\"$listId\" class=\"list\"></ul>";
        return $output;
    }

    public static function getStyles() {
        return '<style>
ul.inline-list {
  padding: 14px;
}
ul.inline-list li {
  display: inline;
  margin-left: 2px;
}
ul.list {
  border: 1px solid #ddd;
  border-radius: 2px;
  padding: 5px;
}
ul.list li {
  list-style-type: none;
  padding: 1px 0 1px 4px;
}
ul.list li:hover {
  background-color: #ccc;
}
</style>';
    }

    public function renderScripts() {
      extract($this->properties);
        return '$(document).ready( function() {
            ajaxSearch( "' .$inputId .'", "' .$listId .'", "styles", true, function($item, input, list, state) {
              console.log(input.attr("id"));
            }, {} );
        } );';
    }
}
