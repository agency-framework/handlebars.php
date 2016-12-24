<?php

namespace AgencyFramework\Handlebars;


class Core
{

    const DEF_DEFAULT_GROUP = 'default';
    protected static $instance = null;
    protected static $options = null;


    /**
     * @var \Handlebars\Handlebars
     */
    protected $engine;
    protected $globalDefTemp;
    protected $globalMixinPath;
    protected $globalMixinDeactivated;
    private $partialsDefaultData = [];
    protected $defDefaultGroup;

    /**
     * @return \AgencyFramework\Handlebars\Core
     */
    public static function getInstance()
    {
        self::hasInstance();
        return self::$instance;
    }

    public function __construct($engine, $globalDefTemp, $globalMixinPath, $globalMixinDeactivated, $defaultDefGroup)
    {
        $this->engine = $engine;
        $this->globalDefTemp = $globalDefTemp;
        $GLOBALS[$globalDefTemp] = null;
        $this->globalMixinDeactivated = $globalMixinDeactivated;
        $GLOBALS[$globalMixinDeactivated] = null;
        $this->globalMixinPath = $globalMixinPath;
        $GLOBALS[$globalMixinPath] = [];
        $this->defDefaultGroup = $defaultDefGroup;
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
            if (!array_key_exists('globalDefTemp', $options)) {
                $options['globalDefTemp'] = 'AGENCY_BOILERPLATE_HBS_DEF_TEMP';
            }
            if (!array_key_exists('globalMixinPath', $options)) {
                $options['globalMixinPath'] = 'AGENCY_BOILERPLATE_HBS_MIXIN_PATH';
            }
            if (!array_key_exists('globalMixinDeactivated', $options)) {
                $options['globalMixinDeactivated'] = 'AGENCY_BOILERPLATE_HBS_MIXIN_DEACTIVATED';
            }
            if (!array_key_exists('defDefaultGroup', $options)) {
                $options['defDefaultGroup'] = self::DEF_DEFAULT_GROUP;
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
        )), $options['globalDefTemp'], $options['globalMixinPath'], $options['globalMixinDeactivated'], $options['defDefaultGroup']);
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

    public function getGlobalDefTemp()
    {
        return $this->globalDefTemp;
    }

    public function getGlobalMixinPath()
    {
        return $this->globalMixinPath;
    }

    public function getGlobalMixinDeactivated()
    {
        return $this->globalMixinDeactivated;
    }

    public function getDefDefaultGroup()
    {
        return $this->defDefaultGroup;
    }

    public function getDefData($partialName, $properties = null)
    {
        $core = \AgencyFramework\Handlebars\Core::getInstance();

        // force partial load, for get yaml data
        $core->getEngine()->getPartialsLoader()->load($partialName);

        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalDefTemp()] = [];
        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalMixinDeactivated()] = true;
        $core->getEngine()->render($partialName, $core::getDefaultPartialData($partialName));
        $GLOBALS[\AgencyFramework\Handlebars\Core::getInstance()->getGlobalMixinDeactivated()] = false;

        $key = explode('/', $partialName);
        $key = $key[count($key) - 1];
        $properties[$key] = $GLOBALS[$core->getGlobalDefTemp()];
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

    public function renderPage($pagePath, $data)
    {


//        echo '<pre>';
//        print_r(array_merge_recursive(array(
//
//            'options' => [
//                'scripts' => [
//                    'css' => [
//                        'critical' => '#scripts.css.critical'
//                    ],
//                    'js' => [
//                        'main' => '#scripts.js.main'
//                    ]
//                ],
//                'server' => [
//                    'dest' => '#scripts.server.dest'
//                ]
//            ],
//
//            'body' => $html), $data));
//        echo '</pre>';

        $data = array_merge_recursive([
            'options' => [
                'scripts' => [
                    'css' => [
                        'main' => './export/css/style.css',
                        'critical' => './export/css/critical.css'
                    ],
                    'js' => [
                        'main' => './export/js/app.js',
                        'embed' => [
                            "./export/js/embed/_main.js",
                            "./export/js/embed/animationFrame.js",
                            "./export/js/embed/agency_pkg_polyfills.js",
                            "./export/js/embed/agency_pkg_service_dev_tools.js"
                        ]
                    ]
                ],
                'server' => [
                    'dest' => './'
                ]
            ],

            'relativeToRoot' => './'

        ], $data);

        $body = $this->getEngine()->render('index', $data);
        return str_replace('{% body %}', $body, $this->getEngine()->render($this->partialsDefaultData[$pagePath]['layout'], $data));


    }

    public static function setVar($vars, $value, &$data)
    {
        if (is_string($vars)) {
            $vars = explode('/', $vars);
        }
        $var = array_shift($vars);
        if (count($vars) > 0) {
            $data[$var] = self::setVar($vars, $value, $data[$var]);
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
        print_r($subContext);
        return $subContext[$varName];
    }

}

?>
