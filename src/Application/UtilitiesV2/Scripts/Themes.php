<?php
	declare(strict_types=1); //Created at 2019-06-17 05:57:45 by 5280

	namespace Framework\Application\UtilitiesV2\Scripts;

	/**
	 * Class Themes
	 * @package Framework\Application\UtilitiesV2\Scripts
	 */
	class Themes extends Theme
	{

	    /**
	     * The logic of your script goes in this function.
	     *
	     * @param $arguments
	     * @return bool
	     */

	    public function execute($arguments)
	    {
	        return parent::execute($arguments); // TODO: Change the autogenerated stub
	    }

	    /**
	     * Example:
	     *  [
	     *      "file"
	     *      "name"
	     *  ]
	     *
	     *  View from the console:
	     *      Themes file=myfile.php name=no%space
	     *
	     * @return array|null
	     */

	    public function requiredArguments()
	    {

	        return parent::requiredArguments();
	    }
	}