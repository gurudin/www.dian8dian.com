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
        'yii\web\YiiAsset',
        'backend\assets\BootstrapAsset',
        'backend\assets\BaseAsset',
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
                    'depends' => 'backend\assets\BaseAsset',
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
