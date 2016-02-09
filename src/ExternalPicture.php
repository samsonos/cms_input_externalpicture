<?php
/**
 * Created by PhpStorm.
 * User: myslyvyi
 * Date: 13.01.2016
 * Time: 17:40
 */

namespace samsoncms\input\externalpicture;

use \samsoncms\input\Field;

class ExternalPicture extends Field
{
    /** Database object field name */
    protected $param = 'Value';

    /** Special CSS classname for nested field objects to bind JS and CSS */
    protected $cssClass = '__externalPictureField';


    /**
     * Function to render class
     *
     * @param Application $renderer Renderer object
     * @param string $saveHandler Save controller name
     * @return string HTML string
     */
    public function view($renderer, $saveHandler = 'save')
    {
        // Current value from DB
        $value = $this->value();
        // Checked for default value
        $defaultValue = ($value == 'http://' || $value == '') ? true : false;

        return $renderer->view($this->defaultView)
            ->set('cssClass', $this->cssClass)
            ->set('value', $value)
            ->set('defaultValue', $defaultValue)
            ->set('action', url_build(preg_replace('/(_\d+)/', '', $renderer->id()), $saveHandler))
            ->set('clearFieldController',  url_build(preg_replace('/(_\d+)/', '', $renderer->id()), 'clearField'))
            ->set('paramController', '?e='.$this->entity.'&p='.$this->param.'&i='.$this->dbObject->id)
            ->set('textInput', t('Введите значение начиная с http://',true))
            ->set('textClearConfirm', t('Очистить поле?',true))
            ->set('entity', $this->entity)
            ->set('param', $this->param)
            ->set('objectId', $this->dbObject->id)
            ->set('applicationId', $renderer->id())
            ->set('fieldView', $this->viewField($renderer))
            ->output();
    }
}