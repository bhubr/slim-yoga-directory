<?php
/**
 * Slim Twig Component.
 * 
 * @link https://github.com/bhubr/... for the canonical source repository
 *
 * @copyright Copyright (c) 2017 Benoit Hubert
 */

// use Twig_Extension;
// use Twig_SimpleFunction;

class TwigComponents extends Twig_Extension
{
    /**
     * @var Singleton
     * @access private
     * @static
     */
    private static $_instance = null;

    private $_components = [];
    private $_styles = [];
 
    /**
     * Constructor
     *
     * @param void
     * @return void
     */
    private function __construct() {  
    }

    public static function getInstance() {
    
        if(is_null(self::$_instance)) {
            self::$_instance = new TwigComponents();  
        }
    
        return self::$_instance;
    }


    /**
     * Extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'slim-twig-components';
    }

    /**
     * Callback for twig.
     *
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('renderHtml', [$this, 'renderHtml'], ['is_safe' => ['html'] ] ),
            new Twig_SimpleFunction('renderStyles', [$this, 'renderStyles'], ['is_safe' => ['html'] ] ),
            new Twig_SimpleFunction('renderScripts', [$this, 'renderScripts'], ['is_safe' => ['html'] ] ),
        ];
    }

    public function register( $handle, $instance ) {
        $this->_components[$handle] = $instance;
        $theClass = get_class( $instance );
        if( !array_key_exists($theClass, $this->_styles) ) {
            $this->_styles[$theClass] = $theClass::getStyles();
        }
    }

    /**
     *
     * @param string $key
     *
     * @return array
     */
    public function renderHtml( $handle )
    {
        return $this->_components[$handle]->renderHtml();
    }
 
    public function renderStyles()
    {
        $styles = "<style>";
        foreach($this->_styles as $css) $styles .= $css;
        // var_dump($styles);die();
        $styles .= "</style>";
        return $styles;
    }

    public function renderScripts()
    {
        $script = "<script>";
        foreach($this->_components as $component) {
             $script .= $component->renderScripts();
        }
        $script .= "</script>";
        return $script;
    }}
