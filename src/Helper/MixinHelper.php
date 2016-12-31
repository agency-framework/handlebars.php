<?php

namespace AgencyFramework\Handlebars\Helper;

use AgencyFramework\Handlebars\Core;
use Handlebars\Context;
use Handlebars\StringWrapper;

class MixinHelper extends \JustBlackBird\HandlebarsHelpers\Layout\AbstractBlockHelper implements \Handlebars\Helper
{

    protected $level = 0;

    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {

        /** @var $template \Handlebars\Template */
        $parsedArgs = $template->parseArguments($args);
        $partialName = $context->get(array_shift($parsedArgs));
        $parsedNamedArgs = $template->parseNamedArguments($args);
        $scope = [];

        if (count($parsedArgs) > 0) {
            for ($i = 0; $i < count($parsedArgs); $i++) {
                if (is_array($context->get(str_replace('this.', '../', $parsedArgs[$i])))) {
                    Core::deepMerge($scope, $context->get(str_replace('this.', '../', $parsedArgs[$i])), true);
                }
            }
        }

        foreach ($parsedNamedArgs as $key => $arg) {
            $scope[$key] = $arg;
        }

        $core = \AgencyFramework\Handlebars\Core::getInstance();
        // preload for forced yaml exclude and saved temp.
        $core->getDefaultPartialData($partialName);

        $defaultData = array_merge([], $core->getDefaultPartialData($partialName));
        Core::deepMerge($defaultData, $scope,true);

        $scope = array_merge(['relativeToRoot' => $GLOBALS[$core->getGlobalRelativeToRoot()]],$defaultData);

        $context->push($scope);
        $name = explode('/', $partialName);
        $name = end($name);

        array_push($GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalMixinPath()], $name);


        // Added relativeToRoot to Context
        $buffer = null;
        array_push($GLOBALS[Core::getInstance()->getGlobalBlocks()], []);
        $GLOBALS[Core::getInstance()->getGlobalBlocksIndex()]++;
        if ($source) {
            $template->render($context);
            $this->level++;
            $buffer = $template->getEngine()->render($partialName, $context);
            $this->level--;
        } else {
            $this->level++;
            $buffer = $template->getEngine()->render($partialName, $context);
            $this->level--;
        }
        $GLOBALS[Core::getInstance()->getGlobalBlocksIndex()]--;
        array_pop($GLOBALS[Core::getInstance()->getGlobalBlocks()]);
        if ($this->level == 0) {
            $this->blocksStorage->clear();
        }
        $context->pop();
        array_pop($GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalMixinPath()]);
        return $buffer;
    }

}