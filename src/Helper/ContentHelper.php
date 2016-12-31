<?php

namespace AgencyFramework\Handlebars\Helper;

use AgencyFramework\Handlebars\Core;
use Handlebars\Helper as HelperInterface;

class ContentHelper extends \JustBlackBird\HandlebarsHelpers\Layout\OverrideHelper implements HelperInterface
{

    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        // Get block name
        $parsed_args = $template->parseArguments($args);
        if (count($parsed_args) != 1) {
            throw new \InvalidArgumentException(
                '"override" helper expects exactly one argument.'
            );
        }

        $block_name = $context->get(array_shift($parsed_args));

        $index = $GLOBALS[Core::getInstance()->getGlobalBlocksIndex()];
        $GLOBALS[Core::getInstance()->getGlobalBlocks()][$index][$block_name] = $template->render($context);

    }
}

?>
