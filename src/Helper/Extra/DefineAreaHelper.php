<?php

namespace AgencyFramework\Handlebars\Helper\Extra;

use AgencyFramework\Handlebars\Core;
use Handlebars\Context;
use Handlebars\Helper;
use Handlebars\Template;

/**
 * The Area Helper
 * Placeholder for dynamic content
 */
class DefineAreaHelper implements Helper
{

    public function execute(Template $template, Context $context, $args, $source)
    {
        $core = Core::getInstance();

        /**
         * Define Global Area-Definition
         * Disable with global "globalDisableExtraDefineArea" inner Area-Definitions
         */
        $setGlobal = $GLOBALS[$core->getGlobalDisableExtraDefineArea()] === false;
        if ($GLOBALS[$core->getGlobalDisableExtraDefineArea()] === false) {
            $namedArgs = $template->parseNamedArguments($args);
            $GLOBALS[Core::getInstance()->getGlobalExtraDefinitionsArea()][] = [
                'name' => $namedArgs['name']
            ];
            $setGlobal = true;
            $GLOBALS[$core->getGlobalDisableExtraDefineArea()] = true;
        }

        $buffer = '';

        $tmp = current($template->parseArguments($args));
        $tmp = $context->get($tmp);

        if (!$source && is_array($tmp) || $tmp instanceof \Traversable) {
            // Without Item Wrapper
            foreach ($tmp as $key => $var) {
                $partial = $var['partial'];
                $elementData = $var;
                unset ($elementData['partial']);
                $buffer .= renderPartial($core, $partial, $elementData);
            }
        } else {

            // With Item Wrapper
            if (!$tmp) {
                $template->setStopToken('else');
                $template->discard();
                $template->setStopToken(false);
                $buffer = $template->render($context);
            } elseif (is_array($tmp) || $tmp instanceof \Traversable) {
                $isList = is_array($tmp) && (array_keys($tmp) === range(0, count($tmp) - 1));
                $index = 0;
                $lastIndex = $isList ? (count($tmp) - 1) : false;

                foreach ($tmp as $key => $var) {

                    $partial = $var['partial'];
                    $elementData = $var;
                    unset ($elementData['partial']);

                    $specialVariables = array(
                        '@index' => $index,
                        '@first' => ($index === 0),
                        '@last' => ($index === $lastIndex),
                        '@element' => renderPartial($core, $partial, $elementData)
                    );
                    if (!$isList) {
                        $specialVariables['@key'] = $key;
                    }
                    $context->pushSpecialVariables($specialVariables);
                    $context->push($var);
                    $template->setStopToken('else');
                    $template->rewind();
                    $buffer .= $template->render($context);
                    $context->pop();
                    $context->popSpecialVariables();
                    $index++;
                }
                $template->setStopToken(false);
            }
        }
        if ($setGlobal) {
            $GLOBALS[$core->getGlobalDisableExtraDefineArea()] = false;
        }
        return $buffer;
    }
}

/**
 * @param \AgencyFramework\Handlebars\Core $core
 * @param string $partial
 * @param mixied $data
 * @return mixed
 */
function renderPartial($core, $partial, $data)
{
    return $core->getEngine()->render($partial, array_merge($core->getDefaultPartialData($partial), $data));
}