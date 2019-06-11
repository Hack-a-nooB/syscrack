<?php
	declare(strict_types=1);

	namespace Framework\Application\UtilitiesV2\Scripts;

	/**
	 * Class Globals
	 *
	 * Automatically created at: 2018-09-01 19:49:11
	 */

	use Framework\Application\UtilitiesV2\Debug;
	use Framework\Application\UtilitiesV2\Format;

	/**
	 * Class Globals
	 * @package Framework\Application\UtilitiesV2\Scripts
	 */
	class Globals extends Base
	{

		/**
		 * @param $arguments
		 *
		 * @return bool
		 */
		public function execute($arguments)
		{

			$keys = array_keys($arguments);

			if (empty($keys) == false)
				if ($keys[0] == "json")
					$json = true;
				else
					$json = false;
			else
				$json = false;

			if ($json)
				Debug::echo(Format::toJson(get_defined_constants(true)["user"], true));
			else
				foreach (get_defined_constants(true)["user"] as $key => $constant)
					Debug::echo("[" . $key . "] : " . $constant);

			return parent::execute($arguments); // TODO: Change the autogenerated stub
		}

		/**
		 * @return array
		 */

		public function help()
		{
			return ([
				"arguments" => $this->requiredArguments(),
				"help" => "Here you can supply a bunch of useful information for your script!"
			]);
		}

		/**
		 * @return array|null
		 */

		public function requiredArguments()
		{

			return parent::requiredArguments();
		}
	}