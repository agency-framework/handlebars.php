<?php
namespace AgencyFramework\Handlebars\Helper;

class Base64Helper implements \Handlebars\Helper
{
    /**
     * Override "with" helper for remove buffer output, when arguments empty.
     * @param \Handlebars\Template $template
     * @param \Handlebars\Context $context
     * @param array $args
     * @param string $source
     * @return mixed
     */
    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        $parsedArgs = $template->parseArguments($args);
        return base64_encode(file_get_contents($context->get(current($parsedArgs))));
    }
}

?>
