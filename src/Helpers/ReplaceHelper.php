<?php
namespace AgencyFramework\Handlebars\Helpers;

use AgencyFramework\Handlebars\Core;

class ReplaceHelper implements \Handlebars\Helper
{
    /**
     * @param \Handlebars\Template $template
     * @param \Handlebars\Context $context
     * @param array $args
     * @param string $source
     * @return string
     */
    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        $parsedArgs = $template->parseArguments($args);
        return str_replace(Core::getVarFromContext($parsedArgs[0], $context), Core::getVarFromContext($parsedArgs[1], $context), Core::getVarFromContext($parsedArgs[2], $context));
    }


}

?>
