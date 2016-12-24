<?php

namespace AgencyFramework\Handlebars\Helpers;

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
        // If the block is not overridden render and show the default value
        if (!$this->blocksStorage->has($block_name) && $source) {
            return $template->render($context);
        }
        return $this->blocksStorage->get($block_name);
    }

}
