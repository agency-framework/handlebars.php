<?php
namespace AgencyFramework\Handlebars\Helpers;

class RawHelper implements \Handlebars\Helper
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
        return file_get_contents($context->get(current($parsedArgs)));
    }
}

?>
