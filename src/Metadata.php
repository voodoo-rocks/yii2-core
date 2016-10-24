<?php
/**
 * @copyright Copyright (c) 2013-2016 Voodoo Mobile Consulting Group LLC
 * @link      https://voodoo.rocks
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace vr\core;

use Yii;
use yii\base\Component;
use yii\base\Controller;
use yii\base\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Class Metadata
 * @package vr\core
 */
class Metadata extends Component
{

    const CONTROLLER_FILE = 'Controller.php';
    const ACTION_METHOD   = 'action';

    /**
     * @param Module $module
     *
     * @return array
     */
    public function getModuleControllers(Module $module)
    {
        $controllers = [];
        $path        = $module->getControllerPath();

        $files = array_diff(scandir($path), ['..', '.']);
        asort($files);

        foreach ($files as $file) {
            if (strcmp(mb_substr($file, -mb_strlen(self::CONTROLLER_FILE)), self::CONTROLLER_FILE) === 0) {
                $id         = Inflector::camel2id(mb_substr(basename($file), 0, -mb_strlen(self::CONTROLLER_FILE)));
                $controller = $this->getModuleController($module, $id);

                if ($controller) {
                    $controllers[$id] = $this->getControllerActions($controller);
                }
            }
        }

        return $controllers;
    }

    /**
     * @param \yii\base\Module $module
     * @param string           $id
     *
     * @return \yii\base\Controller $controller
     */
    public function getModuleController(Module $module, $id)
    {
        $className = sprintf('%s\%sController', $module->controllerNamespace, Inflector::id2camel($id));

        if (strpos($className, '-') === false && class_exists($className)
            && is_subclass_of($className, 'yii\base\Controller')
        ) {
            return new $className($id, $module);
        }

        return null;
    }

    /**
     * @param Controller $controller
     *
     * @return array
     */
    public function getControllerActions(Controller $controller)
    {
        $actions = array_keys($controller->actions());

        $class = new \ReflectionClass($controller);

        foreach ($class->getMethods() as $method) {
            $name = $method->getName();

            if ($method->isPublic() && !$method->isStatic() && mb_strpos($name, self::ACTION_METHOD) === 0
                && $name !== 'actions'
            ) {
                if (\Yii::$app->id == $controller->module->id) {
                    continue;
                }
                $action = Inflector::camel2id(mb_substr($name, mb_strlen(self::ACTION_METHOD)));

                $actions[] = $action;
            }
        }

        asort($actions);

        return $actions;
    }

    /**
     * @param      $className
     * @param null $forModule
     *
     * @return array
     */
    public function getModulesOf($className, $forModule = null)
    {
        $modules = [];
        $prefix  = null;

        if (!$forModule) {
            $forModule = \Yii::$app;
        } else {
            $prefix = $forModule->id . '/';
        }

        foreach ($forModule->modules as $key => $module) {

            if (is_array($module)) {
                $module = ArrayHelper::getValue($module, 'class');
            }

            if ((new \ReflectionClass($module))->isSubclassOf($className)) {
                $modules[$prefix . $key] = $module;
                $modules += $this->getModulesOf($className, $forModule->getModule($key));
            }
        }

        return $modules;
    }
} 