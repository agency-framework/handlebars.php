<?php

namespace AgencyFramework\Handlebars\Helper;

class IfHelper implements \Handlebars\Helper
{
    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        $parsedArgs = $template->parseArguments($args);
        $tmp = $context->get($parsedArgs[0]);
        if ($tmp && $tmp !== 'false') {
            $template->setStopToken('else');
            $buffer = $template->render($context);
            $template->setStopToken(false);
            $template->discard($context);
        } else {
            $template->setStopToken('else');
            $template->discard($context);
            $template->setStopToken(false);
            $buffer = $template->render($context);
        }
        return $buffer;
    }
}