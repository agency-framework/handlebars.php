<?php

namespace AgencyFramework\Handlebars;

class Helpers extends \Handlebars\Helpers
{

    protected function addDefaultHelpers()
    {
        parent::addDefaultHelpers();

        $storage = new \JustBlackBird\HandlebarsHelpers\Layout\BlockStorage();
        $this->add('block', new \AgencyFramework\Handlebars\Helper\BlockHelper($storage));
        $this->add('mixin', new \AgencyFramework\Handlebars\Helper\MixinHelper($storage));
        $this->add('content', new \AgencyFramework\Handlebars\Helper\ContentHelper($storage));

        $this->add('lookup', new \AgencyFramework\Handlebars\Helper\LookupHelper());
        $this->add('raw', new \AgencyFramework\Handlebars\Helper\RawHelper());
        $this->add('relative', new \AgencyFramework\Handlebars\Helper\RelativeHelper());
        $this->add('replace', new \AgencyFramework\Handlebars\Helper\ReplaceHelper());
        $this->add('extname', new \AgencyFramework\Handlebars\Helper\ExtnameHelper());
        $this->add('base64', new \AgencyFramework\Handlebars\Helper\Base64Helper());
        $this->add('stringify', new \AgencyFramework\Handlebars\Helper\StringifyHelper());


        // Override if, remove $context change
        $this->add('if', new \AgencyFramework\Handlebars\Helper\IfHelper());
        $this->add('with', new \AgencyFramework\Handlebars\Helper\WithHelper());

        // defines
        $this->add('define-var', new \AgencyFramework\Handlebars\Helper\Extra\DefineVarHelper());
        $this->add('define-area', new \AgencyFramework\Handlebars\Helper\Extra\DefineAreaHelper());
    }

}

?>
