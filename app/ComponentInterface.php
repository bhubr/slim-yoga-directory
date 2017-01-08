<?php
interface ComponentInterface {
    public function renderHtml();
    public static function getStyles();
    public function renderScripts();
}