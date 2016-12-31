<?php

namespace AgencyFramework\Handlebars;


class Core
{

    const EXTRA_DEFINITIONS_DEFAULT_GROUP = 'default';
    protected static $instance = null;
    protected static $options = null;

    protected $globalRelativeToRoot = 'AGENCY_FRAMEWORK_HBS_GLOBAL_RELATIVE_TO_ROOT';

    // Extra definition

    protected $globalExtraDefinitionsVar = 'AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINITIONS_VAR';
    protected $globalExtraDefinitionsArea = 'AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINITIONS_AREA';
    protected $extraDefinitionsDefaultGroup;


    /**
     * @var \Handlebars\Handlebars
     */
    protected $engine;
    protected $globalBlocks = 'AGENCY_FRAMEWORK_GLOBAL_BLOCKS';
    protected $globalBlocksIndex = 'AGENCY_FRAMEWORK_GLOBAL_BLOCK_INDEX';
    protected $globalMixinPath = 'AGENCY_FRAMEWORK_HBS_GLOBAL_MIXIN_PATH';
    protected $globalDisableMixin = 'AGENCY_FRAMEWORK_HBS_GLOBAL_MIXIN_DEACTIVATED';
    protected $globalDisableExtraDefineArea = 'AGENCY_FRAMEWORK_HBS_GLOBAL_EXTRA_DEFINE_AREA_DEACTIVATED';
    private $partialsDefaultData = [];

    /**
     * @return \AgencyFramework\Handlebars\Core
     */
    public static function getInstance()
    {
        self::hasInstance();
        return self::$instance;
    }

    public function __construct($engine, $options)
    {
        $this->engine = $engine;

        if (array_key_exists('globalRelativeToRoot', $options)) {
            $this->globalRelativeToRoot = $options['globalRelativeToRoot'];
        }
        $GLOBALS[$this->globalRelativeToRoot] = '';
        if (array_key_exists('relativeToRoot', $options)) {
            $this->setRelativeToRoot($options['relativeToRoot']);
        }
        if (array_key_exists('globalExtraDefinitionsVar', $options)) {
            $this->globalExtraDefinitionsVar = $options['globalExtraDefinitionsVar'];
        }
        $GLOBALS[$this->globalExtraDefinitionsVar] = [];
        if (array_key_exists('globalExtraDefinitionsArea', $options)) {
            $this->globalExtraDefinitionsArea = $options['globalExtraDefinitionsArea'];
        }
        $GLOBALS[$this->globalExtraDefinitionsArea] = [];
        if (array_key_exists('extraDefinitionsDefaultGroup', $options)) {
            $this->extraDefinitionsDefaultGroup = $options['extraDefinitionsDefaultGroup'];
        }

        if (array_key_exists('globalMixinPath', $options)) {
            $this->globalMixinPath = $options['globalMixinPath'];
        }
        $GLOBALS[$this->globalMixinPath] = [];


        if (array_key_exists('globalDisableMixin', $options)) {
            $this->globalDisableMixin = $options['globalDisableMixin'];
        }
        $GLOBALS[$this->globalDisableMixin] = false;

        if (array_key_exists('globalDisableExtraDefineArea', $options)) {
            $this->globalDisableExtraDefineArea = $options['globalDisableExtraDefineArea'];
        }
        $GLOBALS[$this->globalDisableExtraDefineArea] = false;

        $GLOBALS[$this->globalBlocks] = [];
        $GLOBALS[$this->globalBlocksIndex] = 0;

    }

    /**
     * @param array $options
     * @return Core|null
     */
    public static function init($options)
    {
        if (is_array($options)) {
            if (!array_key_exists('partialDir', $options)) {
                $options['partialDir'] = [];
            }
            if (!array_key_exists('extension', $options)) {
                $options['extension'] = '.hbs';
            }
            if (!array_key_exists('prefix', $options)) {
                $options['prefix'] = '';
            }
        } else {
            throw new \InvalidArgumentException(
                'empty options'
            );
        }

        self::$options = $options;
        $baseDirs = $options['partialDir'];
        self::$instance = new self(new \Handlebars\Handlebars(array(
            'helpers' => new Helpers(),
            'loader' => new \AgencyFramework\Handlebars\Loader\FilesystemLoader($baseDirs, $options),
            'partials_loader' => new \AgencyFramework\Handlebars\Loader\FilesystemLoader($baseDirs, $options)
        )), $options);
        return self::$instance;
    }

    public static function hasInstance()
    {
        if (!self::$instance) {
            throw new \InvalidArgumentException(
                'init core and get instance from core.'
            );
        }
    }

    /**
     * @return \Handlebars\Handlebars
     */
    public function getEngine()
    {
        self::hasInstance();
        return $this->engine;
    }

    public function getGlobalRelativeToRoot()
    {
        return $this->globalRelativeToRoot;
    }

    public function getGlobalExtraDefinitionsVar()
    {
        return $this->globalExtraDefinitionsVar;
    }

    public function getGlobalExtraDefinitionsArea()
    {
        return $this->globalExtraDefinitionsArea;
    }

    public function getExtraDefinitionsDefaultGroup()
    {
        return $this->extraDefinitionsDefaultGroup;
    }

    public function getGlobalMixinPath()
    {
        return $this->globalMixinPath;
    }

    public function getGlobalDisableMixin()
    {
        return $this->globalDisableMixin;
    }

    public function getGlobalDisableExtraDefineArea()
    {
        return $this->globalDisableExtraDefineArea;
    }

    public function getGlobalBlocks()
    {
        return $this->globalBlocks;
    }

    public function getGlobalBlocksIndex()
    {
        return $this->globalBlocksIndex;
    }

    public function getExtraDefinitionAreaData($partialName)
    {
        // force partial load, for get yaml data
        $this->getEngine()->getPartialsLoader()->load($partialName);
        $GLOBALS[$this->globalExtraDefinitionsArea] = [];
        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalExtraDefinitionsArea()] = [];
        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalDisableMixin()] = true;
        $this->render($partialName, self::getDefaultPartialData($partialName));
        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalDisableMixin()] = false;
        return $GLOBALS[$this->globalExtraDefinitionsArea];
    }

    public function getExtraDefinitionVarData($partialName, $properties = null)
    {
        // force partial load, for get yaml data
        $this->getEngine()->getPartialsLoader()->load($partialName);

        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalExtraDefinitionsVar()] = [];
        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalDisableMixin()] = true;
        $this->render($partialName, self::getDefaultPartialData($partialName));
        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalDisableMixin()] = false;

        $key = explode('/', $partialName);
        $key = $key[count($key) - 1];
        $properties[$key] = $GLOBALS[$this->getGlobalExtraDefinitionsVar()];
        return $properties;
    }

    public function getPathsFromMixins($partialName)
    {
        $core = \AgencyFramework\Handlebars\Core::getInstance();
        $fileContent = $core->getEngine()->getPartialsLoader()->load($partialName);
        preg_match_all("/{{[{#]mixin \\\"([^\"]*)\\\"[^{}]*}}/", $fileContent, $matches);
        return $matches[1];
    }

    public function getDefaultPartialData($partialPath)
    {
        if (array_key_exists($partialPath, $this->partialsDefaultData)) {
            return $this->partialsDefaultData[$partialPath];
        } else {
            $this->getEngine()->getPartialsLoader()->load($partialPath);
            // check file has yaml
            if (array_key_exists($partialPath, $this->partialsDefaultData)) {
                return $this->partialsDefaultData[$partialPath];
            }
        }
        return [];
    }

    public function registerDefaultPartialData($partialPath, $data)
    {
        $this->partialsDefaultData[$partialPath] = $data;
    }

    public static function setVarDeep($vars, $value, &$data)
    {
        if (is_string($vars)) {
            $vars = explode('/', $vars);
        }
        $var = array_shift($vars);
        if (count($vars) > 0) {
            $data[$var] = self::setVarDeep($vars, $value, $data[$var]);
        } else {
            $data[$var] = $value;
        }
        return $data;
    }


    /**
     * @param array|string $vars
     * @param  \Handlebars\Context|array $context
     * @return mixed
     */
    public static function getVarFromContext($vars, $context)
    {
        if (!$context) {
            return null;
        }
        if (is_string($vars)) {
            $vars = explode('.', $vars);
        } else if ($vars instanceof \Handlebars\StringWrapper) {
            return $vars;
        }
        $varName = array_shift($vars);
        if ($varName === 'this' && count($vars) > 0) {
            $varName = array_shift($vars);
        }
        if (is_array($context)) {
            $subContext = $context[$varName];
            if (!is_array($subContext)) {
                return $subContext;
            }
        } else {
            $subContext = $context->get($varName);
        }
        if (count($vars) > 0) {
            return self::getVarFromContext($vars, $subContext);
        }
        return $subContext[$varName];
    }

    public function render($layout, $data)
    {
        if ($this->getRelativeToRoot()) {
            $data['relativeToRoot'] = $this->getRelativeToRoot();
        }
        $html = $this->getEngine()->render($layout, $data);
        $html = preg_replace('/{{!--[\s\S]*?--}}/', 'rhc', $html);
        return $html;
    }

    public function getVarDefinitions()
    {
    }

    public function getAreaDefinitions()
    {
    }

    public function getRelativeToRoot()
    {
        return $GLOBALS[$this->globalRelativeToRoot];
    }

    public function setRelativeToRoot($relativeToRoot)
    {
        $GLOBALS[$this->globalRelativeToRoot] = $relativeToRoot;
    }

    /**
     * Merge deep two arrays.
     * Sets override, for apply all values from other array.
     * @param array $arrayA
     * @param array $arrayB
     * @param bool $override Override all Values.
     * @return mixed
     */
    public static function deepMerge(&$arrayA, $arrayB, $override = false)
    {

        if (array_key_exists(0, $arrayA) && (array_key_exists(1, $arrayA) || count($arrayA) === 1)) {
            /*
             * Simple Array and Array Object-Lists
             */
            foreach ($arrayB as $key => $value) {
                if (is_array($value) && array_key_exists($key, $arrayA) && is_array($arrayA[$key])) {
                    $arrayA[$key] = array_merge($arrayA[$key], $value);
                } else if (!array_search($value, $arrayA)) {
                    array_push($arrayA, $value);
                }
            }
        } else {
            /*
             * Object-Array (HashMap)
             */
            foreach ($arrayB as $key => $value) {
                if (array_key_exists($key, $arrayA)) {
                    // Key exists
                    if (is_array($arrayA[$key]) && is_array($value)) {
                        $arrayA[$key] = self::deepMerge($arrayA[$key], $value, $override);

                    } else if ($override) {
                        $arrayA[$key] = $value;
                    }
                } else {
                    $arrayA[$key] = $value;
                }
            }
        }

        return $arrayA;

    }

}

?>
