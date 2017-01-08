<?php
/**
 * Slim Twig Component.
 * 
 * @link https://github.com/bhubr/... for the canonical source repository
 *
 * @copyright Copyright (c) 2017 Benoit Hubert
 */

class Component implements ComponentInterface {

    public function __construct( $properties = [] ) {
        $this->properties = $properties;
    }

    public function renderHtml() {
        $output = '';
        $output .=  "pouet pouet";
        $output .=  "<ul class='pouet-component'>";
        foreach( $this->properties as $k => $v ) {
            $output .= "<li>$k => $v</li>";
        }
        $output .= "</ul>";
        return $output;
    }

    public static function getStyles() {
        return '.pouet-component { background: red; color: white }';
    }

    public function renderScripts() {
        return '$(document).ready( function() {
            console.log("Hello I am ' . $this->properties['name'] . '");
        } );';
    }
}
