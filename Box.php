<?php

namespace insolita\wgadminlte;

use yii\web\JsExpression;
use yii\base\Widget;

/**
 * This is just an example.
 */
class Box extends Widget
{
    const TYPE_INFO = 'info';
    const TYPE_PRIMARY = 'primary';
    const TYPE_SUCCESS = 'success';
    const TYPE_DEFAULT = 'default';
    const TYPE_DANGER = 'danger';
    const TYPE_WARNING = 'warning';

    /**@var string $type color style of widget* */

    public $type = self::TYPE_DEFAULT;

    /**@var boolean $solid is solid box header* */
    public $solid = false;

    /**@var string $tooltip box -tooltip* */
    public $tooltip = '';

    /**@var string $tooltip_placement -top/bottom/left/or right **/
    public $tooltip_placement='bottom';

    /**@var string $title * */
    public $title = '';

    /**@var string $footer * */
    public $footer = '';

    /**@var boolean $collapse show or not Box - collapse button* */
    public $collapse = false;

    /**@var boolean $collapse_remember - set cookies for rememer collapse stage* */
    public $collapse_remember = true;

    /**@var string $custom_tools code of custom box toolbar**/
    public $custom_tools = '';

    /**@var string $left_tools code of custom box toolbar in left corner**/
    public $left_tools = '';


    private $cid = null;

    public function init()
    {
        $this->cid = 'bc_' . $this->getId();
        $this->registerJs();
        echo '<div class="box box-' . $this->type . (!$this->solid ? '' : ' box-solid') . '" id="' . $this->cid . '">'
            . (!$this->title && !$this->collapse && !$this->custom_tools && !$this->left_tools
                ? ''
                : '<div class="box-header"'
                . (!$this->tooltip ? '' : 'data-toggle="tooltip" data-original-title="' . $this->tooltip . '" data-placement="'.$this->tooltip_placement.'"') . '>'
                . (!$this->left_tools?'':'<div class="box-tools pull-left">'.$this->left_tools.'</div>')
                . (!$this->title ? '' : '<h3 class="box-title">' . $this->title . '</h3>')
                . (!$this->collapse
                    ? ''
                    :
                    (!$this->custom_tools ?
                        '<div class="box-tools pull-right"><button class="btn btn-primary btn-xs" data-widget="collapse" id="'
                        . $this->cid . '_btn"><i class="fa fa-minus"></i></button></div>' : ''))
                . (!$this->custom_tools
                    ? ''
                    : '<div class="box-tools pull-right">' . $this->custom_tools
                    . (!$this->collapse
                        ? ''
                        : '<button class="btn btn-primary btn-xs" data-widget="collapse" id="' . $this->cid . '_btn">
                                   <i class="fa fa-minus"></i></button>')
                    . '</div>')
                . '</div>')
            . '<div class="box-body">';
    }

    public function run()
    {
        echo '</div>'
            . (!$this->footer ? '' : "<div class='box-footer'>" . $this->footer . "</div>")
            . '</div>';
    }

    public function registerJs()
    {
        if ($this->collapse_remember && $this->collapse) {
            $view = $this->getView();
            JCookieAsset::register($view);
            ExtAdminlteAsset::register($view);
            $js = new JsExpression(
                'if($.cookie("' . $this->cid . '_state")=="hide"){
                        var box = $("#' . $this->cid . '");
                        var bf = box.find(".box-body, .box-footer");
                        if (!box.hasClass("collapsed-box")) {
                            box.addClass("collapsed-box");
                            bf.hide();
                        }
                   }

            '
            );
            $view->registerJs($js);
        }


    }
}
