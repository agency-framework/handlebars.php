<?php
namespace AgencyFramework\Handlebars\Helper;

class StringifyHelper implements \Handlebars\Helper
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
        return json_encode($context->get(current($parsedArgs)));
    }
}

?>
