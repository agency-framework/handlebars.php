<?php
namespace AgencyFramework\Handlebars\Helper;

use AgencyFramework\Handlebars\Core;

class RelativeHelper implements \Handlebars\Helper
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
        return getRelativePath(
            Core::getVarFromContext($parsedArgs[0], $context),
            Core::getVarFromContext($parsedArgs[1], $context)
        );
    }
}

/**
 * @param string $from
 * @param string $to
 * @return string
 */
function getRelativePath($from, $to)
{
    $from = realpath($from);
    $to = realpath(dirname($to)) . '/' . basename($to);
    if (!$from || !$to) {
        return false;
    }
    if (!is_dir($from)) {
        $from = dirname($from);
    }
    $from = explode(DIRECTORY_SEPARATOR, $from);
    $to = explode(DIRECTORY_SEPARATOR, $to);
    for ($i = 0; $i < count($from) && $i < count($to); $i++) {
        if ($from[$i] != $to[$i]) {
            break;
        }
    }
    $from = array_splice($from, $i);
    $to = array_splice($to, $i);
    $up = str_repeat('..' . DIRECTORY_SEPARATOR, count($from));
    $down = implode(DIRECTORY_SEPARATOR, $to);
    return $up . $down;
}

?>
