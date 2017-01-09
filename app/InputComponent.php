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
        $defaults = [
            'multi' => false,
            'renderInput' => true,
            'tagWrap' => 'ul'
        ];
        foreach( $defaults as $k => $v ) {
            if( !array_key_exists($k, $properties) ) {
                $properties[$k] = $v;
            }
        }
        $this->properties = $properties;
    }

    public function renderHtml() {
        extract($this->properties);
        $output = '';
        // $output .=  "<h4>Input component #$inputId</h4>";
        // $output .=  "<ul class='pouet-component'>";
        // foreach( $this->properties as $k => $v ) {
        //     $output .= "<li>$k => $v</li>";
        // }
        // $output .= "</ul>";
        if( $renderInput ) {
            $output .= "<input id=\"$inputId\" class=\"form-control\" placeholder=\"$placeholder\" name=\"totoname\" type=\"name\" value=\"\">";    
        }
        $output .= "<$tagWrap id=\"$listId\" class=\"list\"></$tagWrap>";
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
            ajaxSearch( "' .$inputId .'", "' .$listId .'", "/search-places", true, function($item, input, list, state) {
              console.log(input.attr("id"));
            }, {}, { multi: ' . $multi . ', otherInputs: { "search-city": "city_id", "search-style": "styles" }, template: "entry-template" } );
        } );';
    }
}
