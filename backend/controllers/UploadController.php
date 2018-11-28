<?php
namespace backend\controllers;

use Yii;
use yii\web\UploadedFile;
use Grafika\Grafika;
use Grafika\Color;

class UploadController extends BaseController
{
    /**
     * Upload file
     *
     * @param file file
     */
    public function actionAjaxUpload()
    {
        $file = UploadedFile::getInstanceByName('file');
        $dir  = 'resource/' . date('Ym');
        $path = $dir . '/' . $this->randstr(5) . date('YmdHis') . '.' . $file->getExtension();

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        if ($file->saveAs(Yii::getAlias("@webroot") . '/' . $path) === true) {
            return ['status' => true, 'path' => '/' . $path, 'data' => $file];
        } else {
            return ['status' => false, 'msg' => 'Failed to upload.'];
        }
    }

    /**
     * Generate cover images.
     *
     * @param string title
     *
     * @return mixed
     */
    public function actionAjaxGenerate()
    {
        $width     = 520;
        $height    = 192;
        $font_size = 40;
        $font_ttc  = Yii::$app->basePath . '/web/webfonts/LiberationSans-Regular.ttf';
        $text      = $this->args['title'];
        
        // location
        $box  = imagettfbbox($font_size, 0, $font_ttc, $text);
        $left = ceil(($width - $box[2] - $box[0]) / 2);
        $top  = floor(($height - ($box[1] - $box[7])) / 2);

        // set image
        try {
            $editor = Grafika::createEditor();
            $image = Grafika::createBlankImage($width, $height);
            $editor->draw(
                $image,
                Grafika::createDrawingObject(
                    'Rectangle',
                    $width,
                    $height,
                    [0, 0],
                    null,
                    null,
                    new Color($this->colorBox())
                )
            );
            
            $editor->text(
                $image,
                $text, // Text
                $font_size, // Font size
                $left, // Margin left
                $top, // Margin top
                new Color("#ffffff"),
                $font_ttc, // Font path
                0
            );

            $path = 'resource/' . date('Ym') . '/' . $this->randstr(5) . date('YmdHis') . '.png';
            $editor->save(
                $image,
                $path
            );

            return ['status' => true, 'msg' => 'success', 'path' => '/' . $path];
        } catch (\Throwable $th) {
            return ['status' => false, 'msg' => 'Failed to create.'];
        }
    }

    /**
     * Colors box
     */
    private function colorBox()
    {
        $box = ['#990066','#333366','#003399','#CCFF99','#999933','#333333','#3366CC','#333300','#666666','#FFFF66','#336666','#999999','#0099CC','#CCCCCC','#663366','#6699CC','#666699','#CCFF66','#003366','#99CCFF','#336699','#CCCC33','#0099FF','#FFFFCC','#99CC33','#000000','#3399CC','#CC99CC','#CC3399','#FFCCCC','#FF99CC','#CCCCFF','#9933CC','#996666','#CC9999','#FF9999','#996699','#FFFF99','#CC9933','#CC9966','#CCCC66','#669999','#FF9966','#996600','#CCCC00','#660033','#CC6600','#666600','#009999','#FFCC33',];

        return $box[rand(0, 49)];
    }
}
