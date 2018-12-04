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

    /**
     * 当前分类
     *
     * @var array
     */
    public $current;

    public function __construct()
    {
        $this->category = Category::getAll();
        $this->setNav();
        $this->getCurrent();
    }

    /**
     * Get routes
     */
    public static function routes()
    {
        $home_route = ['uri' => '/', 'title' => '首页'];
        $routes = [];
        $tmp_arr = explode('?', substr(Yii::$app->request->url, 1, strlen(Yii::$app->request->url)));
        $route_arr = explode('/', $tmp_arr[0]);

        if ($route_arr[0] == '' || ($route_arr[0] == 'site' && $route_arr[1] == 'index')) {
            $routes[] = $home_route;
        }

        /** Nav */
        if ($route_arr[0] == 'nav') {
            $res = (new self)->getCategory(['alias' => $route_arr[1]]);
            $routes[] = ['uri' => "/{$route_arr[0]}/$route_arr[1]", 'title' => $res->category, 'id' => $res->id];

            if ($res->parent_id != 0) {
                while (true) {
                    $tmp_res = (new self)->getCategory(['id' => $res->parent_id]);
                    array_unshift(
                        $routes,
                        ['uri' => "/{$route_arr[0]}/{$tmp_res->alias}", 'title' => $tmp_res->category, 'id' => $tmp_res->id]
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
     * Get one category.
     *
     * @param array ['alias' => 'js]
     *
     * @return array $category
     */
    public static function one(array $where)
    {
        foreach ((new self)->category as $item) {
            if ($item[array_keys($where)[0]] == array_values($where)[0]) {
                return $item;
            }
        }

        return [];
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

    /**
     * Current nav
     */
    private function getCurrent()
    {
        $current = Yii::$app->request->getPathInfo();
        foreach ($this->category as $key => $item) {
            if (in_array($current, ['nav/' . $item['alias']])) {
                $this->current = $item;
            }
        }
    }
}
