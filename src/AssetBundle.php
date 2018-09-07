<?php

namespace yozh\multilingual;

class AssetBundle extends \yozh\base\AssetBundle
{

    public $sourcePath = __DIR__ .'/../assets/';

    public $css = [
        //'css/yozh-multilingual.css',
	    //['css/yozh-multilingual.print.css', 'media' => 'print'],
    ];
	
    public $js = [
        //'js/yozh-multilingual.js'
    ];
	
    public $depends = [
        //'yii\bootstrap\BootstrapAsset',
    ];	
	
	public $publishOptions = [
		//'forceCopy'       => true,
	];
	
}