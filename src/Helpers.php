<?php

namespace AgencyFramework\Handlebars;

class Helpers extends \Handlebars\Helpers
{

   protected function addDefaultHelpers()
   {
      parent::addDefaultHelpers();

      $storage = new \JustBlackBird\HandlebarsHelpers\Layout\BlockStorage();
      $this->add('block', new \JustBlackBird\HandlebarsHelpers\Layout\BlockHelper($storage));
      $this->add('mixin', new \AgencyFramework\Handlebars\Helpers\MixinHelper($storage));
      $this->add('with', new \AgencyFramework\Handlebars\Helpers\WithHelper());
      $this->add('content', new \JustBlackBird\HandlebarsHelpers\Layout\OverrideHelper($storage));
      $this->add('lookup', new \AgencyFramework\Handlebars\Helpers\LookupHelper());
      $this->add('stringify', new \AgencyFramework\Handlebars\Helpers\StringifyHelper());
      $this->add('def', new \AgencyFramework\Handlebars\Helpers\DefHelper());
   }

}

?>
