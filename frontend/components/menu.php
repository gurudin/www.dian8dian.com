<?php
namespace frontend\components;

use Yii;
use yii\base\Component;
use common\models\Category;

/**
 * Menu components.
 */
class menu extends Component
{
    /**
     * 页面所用导航
     *
     * @var array
     */
    public $nav;

    /**
     * 所有分类
     *
     * @var array
     */
    public $category;

    public function __construct()
    {
        $this->category = Category::getAll();
        $this->setNav();
    }

    /**
     * Set nav.
     */
    private function setNav()
    {
        foreach ($this->category as $key => $item) {
            if ($item->enabled == 1) {
                $this->nav[] = $item;
            }
        }
    }
}
