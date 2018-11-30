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
     * Get routes
     */
    public static function routes()
    {
        $home_route = ['uri' => '/', 'title' => '首页'];
        $routes = [];

        $route_arr = explode('/', substr(Yii::$app->request->url, 1, strlen(Yii::$app->request->url)));
        if ($route_arr[0] == '' || ($route_arr[0] == 'site' && $route_arr[1] == 'index')) {
            $routes[] = $home_route;
        }

        /** Nav */
        if ($route_arr[0] == 'nav') {
            $res = (new self)->getCategory(['alias' => $route_arr[1]]);
            $routes[] = ['uri' => "/{$route_arr[0]}/$route_arr[1]", 'title' => $res->category];

            if ($res->parent_id != 0) {
                while (true) {
                    $tmp_res = (new self)->getCategory(['id' => $res->parent_id]);
                    array_unshift(
                        $routes,
                        ['uri' => "/{$route_arr[0]}/{$tmp_res->alias}", 'title' => $tmp_res->category]
                    );

                    if ($tmp_res->parent_id == 0) {
                        break;
                    }
                }
            }

            array_unshift($routes, $home_route);
        }

        /** Detail */
        if ($route_arr[0] == 'article') {
            $routes[] = ['uri' => "/{$route_arr[0]}/{$route_arr[1]}/{$route_arr[2]}", 'title' => $route_arr[2]];
            
            array_unshift($routes, $home_route);
        }

        return $routes;
    }

    /**
     * Get category by parent id.
     *
     * @param string $category
     */
    public static function children(string $category)
    {
        $children = [];
        $current  = (new self)->getCategory(['category' => $category]);
        if (empty($current)) {
            return $children;
        }
        
        foreach ((new self)->category as $key => $item) {
            if ($item->parent_id == $current->id) {
                $children[] = $item;
            }
        }

        return $children;
    }

    /**
     * Get category title.
     *
     * @param array ['alias' => 'js']
     */
    private function getCategory(array $arr)
    {
        foreach ($this->category as $item) {
            if ($item[array_keys($arr)[0]] == array_values($arr)[0]) {
                return $item;
            }
        }

        return '';
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
