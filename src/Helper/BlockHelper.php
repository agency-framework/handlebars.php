<?php

namespace AgencyFramework\Handlebars\Helper;

use AgencyFramework\Handlebars\Core;
use Handlebars\Context;
use Handlebars\StringWrapper;


class BlockHelper extends \JustBlackBird\HandlebarsHelpers\Layout\BlockHelper
{
    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        $parsed_args = $template->parseArguments($args);
        if (count($parsed_args) != 1) {
            throw new \InvalidArgumentException(
                '"block" helper expects exactly one argument.'
            );
        }
        $block_name = $context->get(array_shift($parsed_args));

        $block = null;
        if  (count($GLOBALS[Core::getInstance()->getGlobalBlocks()]) > 0) {
            $blocks_ = $GLOBALS[Core::getInstance()->getGlobalBlocks()][$GLOBALS[Core::getInstance()->getGlobalBlocksIndex()]];
            if (!array_key_exists($block_name, $blocks_) && $source) {
                return $template->render($context);
            } else if (array_key_exists($block_name, $blocks_)) {
                $block = $blocks_[$block_name];

            }
        }

        return $block;
    }

}
