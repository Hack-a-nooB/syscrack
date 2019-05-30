<?php
namespace Framework\Application\UtilitiesV2\Scripts;

/**
 * Class Composer
 *
 * Automatically created at: 2019-05-18 05:37:17
 */

use Framework\Application\UtilitiesV2\Debug;
use Framework\Application\UtilitiesV2\Container;

class Composer extends Base
{

    /**
     * The logic of your script goes in this function.
     *
     * @param $arguments
     * @return bool
     */

    public function execute($arguments)
    {

    	Debug::echo('Updating composer w/ Profile');
	    Container::get('scripts')->terminal( "composer update --profile");
        return parent::execute($arguments); // TODO: Change the autogenerated stub
    }

    /**
     * The help index can either be a string or an array containing a set of strings. You can only put strings in
     * there.
     *
     * @return array
     */

    public function help()
    {
        return([
            "arguments" => $this->requiredArguments(),
            "help" => "Hello World"
        ]);
    }

    /**
     * Example:
     *  [
     *      "file"
     *      "name"
     *  ]
     *
     *  View from the console:
     *      Composer file=myfile.php name=no_space
     *
     * @return array|null
     */

    public function requiredArguments()
    {

        return parent::requiredArguments();
    }
}