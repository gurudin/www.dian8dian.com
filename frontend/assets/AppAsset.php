<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/site.css',
        'css/solid.css',
        'css/fontawesome.min.css',
    ];
    public $js = [
        'js/bootstrap.min.js',
        'js/custom.js',
    ];
    public $depends = [
        // 'backend\assets\BaseAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * Import js.
     */
    public static function addScript($view, $jsSet = [])
    {
        foreach ($jsSet as $file) {
            $view->registerJsFile(
                '@web/js/' . $file . '?v=' . @filemtime(\Yii::getAlias('@webroot/js/' . $file)),
                [
                    'depends' => 'yii\bootstrap\BootstrapAsset',
                    'position' => View::POS_HEAD,
                ]
            );
        }
    }

    /**
     * Import css.
     */
    public static function addCss($view, $cssSet = [])
    {
        foreach ($cssSet as $file) {
            $view->registerCssFile(
                '@web/css/' . $file . '?v=' . @filemtime(\Yii::getAlias('@webroot/css/' . $file)),
                [
                    'depends' => 'backend\assets\BaseAsset',
                ]
            );
        }
    }
}
