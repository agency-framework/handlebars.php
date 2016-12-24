<?php

namespace AgencyFramework\Handlebars\Loader;

use AgencyFramework\Handlebars\Core;

class FilesystemLoader extends \Handlebars\Loader\FilesystemLoader
{

    /**
     * Remove yaml from template content.
     * @param string $name template name
     * @return mixed|string
     */
    protected function loadFile($name)
    {
        $filename = $name;
        // if package path, reference to index file.
        if (preg_match("/.*-pkg-.*/", $name, $match) && !preg_match("/\\/default$/", $name, $match)) {
            $filename = preg_replace('/(.*)\/([^\/]*)$/', '$1/tmpl/$2', $filename);
            if (!preg_match('/\/[^\/]*$/', $filename)) {
                $filename .= '/tmpl/default';
            }
        }
        $fileContent = parent::loadFile($filename);
        if (preg_match("/^---[.\\s\\S]*---/", $fileContent, $match)) {
            Core::getInstance()->registerDefaultPartialData($name, \Spyc::YAMLLoad($match[0]));
        }
        // replace yaml
        return preg_replace("/^(---[.\\s\\S]*---)/", "", $fileContent);
    }

}

?>
