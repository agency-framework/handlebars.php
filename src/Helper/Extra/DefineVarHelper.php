<?php
namespace AgencyFramework\Handlebars\Helper\Extra;

use AgencyFramework\Handlebars\Core;
use Handlebars\Context;
use Handlebars\StringWrapper;
use Handlebars\Template;

class DefineVarHelper implements \Handlebars\Helper
{

    private static function setVar(&$data, $path, $value)
    {
        if (!$data) {
            $data = [];
        }
        $path = array_merge([], $path);
        $name = array_shift($path);

        if (!array_key_exists($name, $data) || !is_array($data[$name])) {
            $data[$name] = [];
        }
        if (count($path) > 0) {
            self::setVar($data[$name], $path, $value);
        } else {
            if (!array_key_exists($name, $data) || !is_array($data[$name])) {
                $data[$name] = [];
            }
            $data[$name][] = $value;
        }

        return $data;

    }

    /**
     * @param Template $template
     * @param Context $context
     * @param array $args
     * @param string $source
     * @return mixed
     */
    public function execute(Template $template, Context $context, $args, $source)
    {

        $parsedNamedArgs = $template->parseNamedArguments($args);

        if (!array_key_exists('group', $parsedNamedArgs)) {
            $parsedNamedArgs['group'] = new StringWrapper(Core::getInstance()->getGlobalExtraDefinitionsVar());
        }
        if ($parsedNamedArgs['group'] && !($context->get($parsedNamedArgs['group']) instanceof StringWrapper)) {
            if (!array_key_exists('type', $parsedNamedArgs)) {
                $parsedNamedArgs['type'] = null;
            }
            if (!array_key_exists('desc', $parsedNamedArgs)) {
                $parsedNamedArgs['desc'] = null;
            }
            if (!array_key_exists('default', $parsedNamedArgs)) {
                $parsedNamedArgs['default'] = null;
            } else {
                $parsedNamedArgs['default'] = $context->get($parsedNamedArgs['default']);
            }
            $parsedNamedArgs['group'] = $context->get($parsedNamedArgs['group']);
            if ($parsedNamedArgs['group']) {
                self::setVar($GLOBALS[Core::getInstance()->getGlobalExtraDefinitionsVar()], [(string)$parsedNamedArgs['group']], $parsedNamedArgs);
            } else {
                self::setVar($GLOBALS[Core::getInstance()->getGlobalExtraDefinitionsVar()], [Core::getInstance()->getExtraDefinitionsDefaultGroup()], $parsedNamedArgs);
            }
        }

        return null;
    }

}

?>
