<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/custom.js',
        
    ];
    public $depends = [
        'backend\assets\BaseAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    //定义按需加载JS方法，注意加载顺序在最后
    // public static function addCustomVueExtention($view) {
    //     $view->registerAssetBundle('backend\assets\CustomVueExtentionAsset', View::POS_HEAD);
    // }
    // public $basePath = '@webroot';
    // public $baseUrl = '@web';
    
    // public $css = [
    //     'css/site.css',
    // ];
    // public $js = [
    //     'js/vue.min.js',
    // ];
    // public $depends = [
    //     'yii\web\YiiAsset',
    //     'yii\bootstrap\BootstrapAsset',
    // ];
}
