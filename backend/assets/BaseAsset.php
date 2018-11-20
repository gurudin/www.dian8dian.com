<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class BaseAsset extends AssetBundle
{
    public $sourcePath = '@bower';

    public $js = [
        'vue/dist/vue.js',
    ];
}
