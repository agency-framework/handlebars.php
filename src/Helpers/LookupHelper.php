<?php
namespace AgencyFramework\Handlebars\Helpers;

class LookupHelper implements \Handlebars\Helper
{
    public function execute(\Handlebars\Template $template, \Handlebars\Context $context, $args, $source)
    {
        $parsedArgs = $template->parseArguments($args);
        $scope = [];
        if (count($parsedArgs) > 0) {
            for ($i = 0; $i < count($parsedArgs); $i++) {
                if (is_array($context->get(str_replace('this.', '../', $parsedArgs[$i])))) {
                    $scope = array_merge_recursive($scope, $context->get(str_replace('this.', '../', $parsedArgs[$i])));
                }
            }
        }
        $data = $context->get($parsedArgs[0]);
        if ($data) {
            $value = $context->get($parsedArgs[1]);
            if ($value && array_key_exists($value, $data)) {
                return $data[$value];
            }
        }
    }
}

?>
