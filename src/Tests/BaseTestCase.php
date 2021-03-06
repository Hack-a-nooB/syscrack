<?php
	declare(strict_types=1);
	/**
	 * Created by PhpStorm.
	 * User: newsy
	 * Date: 30/04/2019
	 * Time: 20:39
	 */

	namespace Framework\Tests;

	use Framework\Application;
	use Framework\Application\UtilitiesV2\Debug;
	use PHPUnit\Framework\TestCase;

	/**
	 * Class BaseTestCase
	 * @package Framework\Tests
	 */
	class BaseTestCase extends TestCase
	{

		/**
		 * @var Application
		 */

		protected static $application;

		/**
		 * Starts up syscrack in CMD mode, CMD mode essentially just doesn't run the flight micro engine
		 */

		public static function setUpBeforeClass(): void
		{

			//only does this once
			if (defined("PHPUNIT_FINISHED") == false)
			{

				Debug::setCMD();
				include_once "../index.php";
				self::$application = new Application(false);
				self::$application->addToGlobalContainer();
				define("PHPUNIT_FINISHED", true );
			}

			parent::setUpBeforeClass();
		}
	}