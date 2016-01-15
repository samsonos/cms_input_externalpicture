<?php
/**
 * Created by PhpStorm.
 * User: myslyvyi
 * Date: 13.01.2016
 * Time: 17:43
 */

namespace samsoncms\input\externalpicture;

use samson\activerecord\dbQuery;
/**
 * SamsonCMS External Picture input module
 * @author Dima Myslyvyi <myslyvyi@samsonos.com>
 */
class Application extends \samsoncms\input\Application
{
    /** @var int Field type number */
    public static $type = 13;

    /** @var string SamsonCMS field class */
    protected $fieldClass = '\samsoncms\input\externalpicture\ExternalPicture';

    /**
     * Save handler for CMS Field input
     */
    public function __async_save()
    {
        $response = array('status' => 0);

        // If we have post data
        if (isset($_POST)) {
            // Make pointers to posted parameters
            $entity = & $_POST['__entity'];
            $param 	= & $_POST['__param'];
            $identifier = & $_POST['__obj_id'];
            $value = & $_POST['__value'];

            // Check if all necessary data is passed
            if (!isset($value)) {
                e('CMSField - no "value" is passed for saving', E_SAMSON_CORE_ERROR);
            }
            if (!isset($entity)) {
                e('CMSField - no "entity" is passed for saving', E_SAMSON_CORE_ERROR);
            }
            if (!isset($identifier)) {
                e('CMSField - no "object identifier" is passed for saving', E_SAMSON_CORE_ERROR);
            }
            if (!isset($param)) {
                e('CMSField - no "object field name" is passed for saving', E_SAMSON_CORE_ERROR);
            }


            if (($this->isImage($value) && $this->isUrl($value)) || $value == 'http://') {
                // Create new Field instance
                $this->createField(new dbQuery(), $entity, $param, $identifier);

                $response['status'] = 1;
                // Save specified value to SamsonCMS input
                $this->field->save($value, $response);
            }
        }

        return $response;
    }

    /**
     * async Controller for clear field with value
     * @return array response
     */

    public function __async_clearField()
    {
        // Response if status = 1 success else faid request
        $response = array('status' => 0);

        // If we have get data
        if (isset($_GET['e']) && isset($_GET['p']) && isset($_GET['i'])) {
            // Set success response
            $response['status'] = 1;

            // Create new Field instance
            $this->createField(new dbQuery(), $_GET['e'], $_GET['p'], $_GET['i']);

            // Save specified value to SamsonCMS input
            $this->field->save('http://', $response);
        }

        return $response;
    }

    /**
     * Checked if input value have extension images
     * @param string $value
     * @return bool
     */

    private function isImage($value)
    {
        //Getting extension from value
        $extension =  substr($value, strrpos($value, '.') + 1);
        return ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif');
    }

    /**
     * Checked if input value is URL
     * @param string $url
     * @return bool
     */

    private function isUrl($url) {
        return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
    }
}