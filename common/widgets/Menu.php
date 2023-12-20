<?php
namespace common\widgets;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Class Menu
 * Theme menu widget.
 */
class Menu extends \yii\widgets\Menu
{

    /**
     * @inheritdoc
     * Styles all labels of items on sidebar by AdminLTE
     */
    public $submenuTemplate = "\n<ul class='treeview-menu' {show}>\n{items}\n</ul>\n";
    public $activateParents = true;
    public $defaultIconHtml = '<i class="fa fa-circle-o"></i> ';
    public $options = ['class' => 'sidebar-menu', 'data-widget' => 'tree'];

    /**
     * @var string is prefix that will be added to $item['icon'] if it exist.
     * By default uses for Font Awesome (http://fontawesome.io/)
     */

    private $noDefaultAction;
    private $noDefaultRoute;

    /**
     * Renders the menu.
     */
    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        $posDefaultAction = strpos($this->route, Yii::$app->controller->defaultAction);
        if ($posDefaultAction) {
            $this->noDefaultAction = rtrim(substr($this->route, 0, $posDefaultAction), '/');
        } else {
            $this->noDefaultAction = false;
        }
        $posDefaultRoute = strpos($this->route, Yii::$app->controller->module->defaultRoute);
        if ($posDefaultRoute) {
            $this->noDefaultRoute = rtrim(substr($this->route, 0, $posDefaultRoute), '/');
        } else {
            $this->noDefaultRoute = false;
        }
        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'ul');

            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        return [
            '{label}' => strtr($this->labelTemplate, ['{label}' => $item['label'],]),
            '{icon}' => empty($item['icon']) ? $this->defaultIconHtml
                : "<div class='icon__navbar icon icon-".$item['icon']."'></div>",
            '{url}' => isset($item['url']) ? Url::to($item['url']) : 'javascript:void(0);',
        ];

    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }
            $template = Html::a("{icon} {label}", '{url}', $options);
            $rep = $this->renderItem($item);
            $lines[] = strtr($template, $rep);
        }
        return implode("\n", $lines);
    }

    /**
     * @inheritdoc
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }
        return array_values($items);
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if (isset($route[0]) && $route[0] !== '/' && Yii::$app->controller) {
                $route = ltrim(Yii::$app->controller->module->getUniqueId() . '/' . $route, '/');
            }
            $route = ltrim($route, '/');
            if ($route != $this->route && $route !== $this->noDefaultRoute && $route !== $this->noDefaultAction) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
    protected function getIcons($icon){
        $icons = [
            'tooth' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M4.57378 0.69889C2.92145 0.858525 1.42297 2.04609 0.896608 3.61311C0.51278 4.75583 0.590916 5.76403 1.26908 8.41932C1.86318 10.7456 3.55186 16.2604 3.79923 16.6822C3.92225 16.892 4.15852 17.0983 4.41118 17.2167C4.58871 17.2999 4.67132 17.3148 4.95567 17.3148C5.24002 17.3148 5.32263 17.2999 5.50017 17.2167C5.75215 17.0986 5.98954 16.8916 6.10969 16.6852C6.15833 16.6016 6.49445 15.5953 6.8566 14.4489C7.21879 13.3025 7.55789 12.2849 7.61022 12.1874C7.74314 11.9398 8.05662 11.6567 8.35378 11.516C8.58726 11.4055 8.63385 11.3969 8.99656 11.3969C9.35927 11.3969 9.40586 11.4055 9.63934 11.516C9.93654 11.6568 10.2501 11.9398 10.3829 12.1874C10.4351 12.2849 10.6232 12.8252 10.8008 13.3881C11.8417 16.6878 11.8863 16.8019 12.2337 17.0583C12.432 17.2046 12.8083 17.3334 13.0374 17.3334C13.4586 17.3334 13.9487 17.06 14.1605 16.7069C14.2267 16.5966 14.4088 16.1096 14.5775 15.5917C16.3342 10.1972 17.2446 6.77477 17.3268 5.25565C17.3907 4.07523 16.9701 2.93378 16.1522 2.06805C15.3759 1.24628 14.4658 0.801727 13.3418 0.695279C12.3073 0.597317 11.232 0.905496 10.3373 1.55636C9.93598 1.84834 9.65454 1.98174 9.32158 2.03787C8.72089 2.1391 8.23181 1.99071 7.59584 1.51415C7.03377 1.09297 6.20358 0.775785 5.45846 0.697475C5.0658 0.656236 5.01422 0.65631 4.57378 0.69889Z" fill="#003FDE"/> </svg>',

        ];
        return isset($icons[$icon]) ? $icons[$icon] : null;
    }
}
