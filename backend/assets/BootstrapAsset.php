<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'bootstrap/dist/js/bootstrap.min.js',
    ];

    public $css = [
        'bootstrap/dist/css/bootstrap.min.css',
    ];
}
