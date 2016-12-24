<?php

namespace AgencyFramework\Handlebars;

class Helpers extends \Handlebars\Helpers
{

    protected function addDefaultHelpers()
    {
        parent::addDefaultHelpers();

        $storage = new \JustBlackBird\HandlebarsHelpers\Layout\BlockStorage();
        $this->add('block', new \AgencyFramework\Handlebars\Helpers\BlockHelper($storage));
        $this->add('mixin', new \AgencyFramework\Handlebars\Helpers\MixinHelper($storage));
        $this->add('content', new \JustBlackBird\HandlebarsHelpers\Layout\OverrideHelper($storage));

        $this->add('lookup', new \AgencyFramework\Handlebars\Helpers\LookupHelper());
        $this->add('raw', new \AgencyFramework\Handlebars\Helpers\RawHelper());
        $this->add('relative', new \AgencyFramework\Handlebars\Helpers\RelativeHelper());
        $this->add('replace', new \AgencyFramework\Handlebars\Helpers\ReplaceHelper());
        $this->add('extname', new \AgencyFramework\Handlebars\Helpers\ExtnameHelper());
        $this->add('base64', new \AgencyFramework\Handlebars\Helpers\Base64Helper());
        $this->add('stringify', new \AgencyFramework\Handlebars\Helpers\StringifyHelper());


        // Override if, remove $context change
        $this->add('if', new \AgencyFramework\Handlebars\Helpers\IfHelper());
        $this->add('with', new \AgencyFramework\Handlebars\Helpers\WithHelper());

        $this->add('def', new \AgencyFramework\Handlebars\Helpers\DefHelper());
    }

}

?>
