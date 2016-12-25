<?php
namespace AgencyFramework\Handlebars\Helper;

use AgencyFramework\Handlebars\Core;

class ExtnameHelper implements \Handlebars\Helper
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
        return pathinfo($parsedArgs[0], PATHINFO_EXTENSION);
    }


}

?>
