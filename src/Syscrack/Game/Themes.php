<?php
	declare(strict_types=1);
	/**
	 * Created by PhpStorm.
	 * User: newsy
	 * Date: 30/04/2019
	 * Time: 19:11
	 */

	namespace Framework\Syscrack\Game;

	use Framework\Application\Settings;
	use Framework\Application\Utilities\FileSystem;
	use Framework\Application\UtilitiesV2\Conventions\ThemeData;


	/**
	 * Class Themes
	 * @package Framework\Syscrack\Game
	 */
	class Themes
	{

		/**
		 * @var array
		 */

		protected $themes;

		/**
		 * Themes constructor.
		 *
		 * @param bool $autoread
		 */

		public function __construct($autoread = true)
		{

			if ($autoread)
				$this->getThemes();
		}

		/**
		 * @return mixed
		 */

		public function currentTheme()
		{

			return (Settings::setting("theme_folder"));
		}

		/**
		 * @param $theme
		 */

		public function set($theme)
		{

			if ($this->themeExists($theme) == false)
				throw new \Error("Theme does not exist: " . $theme);

			if ($this->currentTheme() == $theme)
				throw new \Error("Theme already set to: " . $theme);

			if ($this->requiresMVC($theme) && $this->mvcOutput() == false)
				Settings::updateSetting("theme_mvc_output", true);
			else if ($this->requiresMVC($theme) == false && $this->mvcOutput())
				Settings::updateSetting("theme_mvc_output", false);

			if ($this->requiresJson($theme) && $this->jsonOutput() == false)
				Settings::updateSetting("theme_json_output", true);
			else if ($this->requiresJson($theme) == false && $this->jsonOutput())
				Settings::updateSetting("theme_json_output", false);

			Settings::updateSetting("theme_folder", $theme);
		}

		/**
		 * @return bool
		 */

		public function mvcOutput(): bool
		{

			return ((bool)Settings::setting("theme_mvc_output"));
		}

		/**
		 * @return bool
		 */

		public function jsonOutput(): bool
		{

			return ((bool)Settings::setting("theme_json_output"));
		}

		/**
		 * @param $theme
		 *
		 * @return bool
		 */

		public function requiresMVC($theme)
		{

			$data = $this->getData($theme);

			if (empty($data))
				return false;
			else if ( isset( $data["mvc"] ) == false )
				return false;
			else if ($data["mvc"])
				return true;

			return false;
		}

		/**
		 * @param $theme
		 *
		 * @return bool
		 */

		public function requiresJson($theme)
		{

			$data = $this->getData($theme);

			if (empty($data))
				return false;
			else if ( isset( $data["json"] ) == false )
				return false;
			elseif( $data["json"] )
				return true;

			return false;
		}

		/**
		 * @param $theme
		 *
		 * @return bool
		 */

		public function hasBase($theme)
		{

			$data = $this->getData($theme);

			if (empty($data))
				return false;
			else if ( isset( $data["base"] ) == false )
				return false;

			return true;
		}

		/**
		 * @param $theme
		 *
		 * @return bool
		 */
		public function hasAssets($theme)
		{

			$data = $this->getData($theme);

			if (empty($data))
				return false;
			else if ( isset( $data["assets"] ) == false )
				return false;

			return true;
		}

		/**
		 * @param $theme
		 *
		 * @return mixed
		 */

		public function assets( $theme )
		{

			return( $this->getData( $theme )["assets"] );
		}

		/**
		 * @param $theme
		 *
		 * @return mixed
		 */

		public function base( $theme )
		{

			return( $this->getData( $theme )["base"] );
		}

		/**
		 * @param $theme
		 *
		 * @return mixed
		 */

		public function getData($theme)
		{

			return ($this->themes[$theme]["data"]);
		}

		/**
		 * @param $theme
		 * @param ThemeData $object
		 */

		public function modifyInfo($theme, ThemeData $object)
		{

			FileSystem::writeJson($this->path($theme), $object->contents());
		}

		/**
		 * @param $theme
		 * @param bool $object
		 *
		 * @return ThemeData|mixed
		 */

		public function getTheme($theme, $object = true)
		{

			if ($this->themeExists($theme) == false)
				throw new \Error("Theme does not exist: " . $theme);

			$themes = $this->getThemes(false);

			if ($object)
				return self::dataInstance($themes[$theme]);
			else
				return ($themes[$theme]);
		}

		/**
		 * @param $theme
		 *
		 * @return bool
		 */

		public function themeExists($theme)
		{

			if( empty( $this->themes ) )
				$this->getThemes( true );

			return (isset($this->themes[$theme]));
		}

		/**
		 * @param bool $read
		 *
		 * @return array
		 */

		public function getThemes($read = true)
		{

			if ($read)
			{

				$result = $this->gather($this->getFolders());

				if (empty($result))
					return [];

				$this->themes = $result;

				return ($result);
			}
			else if (empty($this->themes))
				return [];
			else
				return ($this->themes);
		}

		/**
		 * @param $folders
		 *
		 * @return array
		 */

		public function gather($folders)
		{

			$info = [];

			foreach ($folders as $folder)
				$info[$folder] = FileSystem::readJson(
					$this->path($folder)
				);

			return ($info);
		}

		/**
		 * @return array|false|null
		 */

		public function getFolders()
		{

			if (FileSystem::directoryExists(Settings::setting("theme_location")) == false)
				throw new \Error("Themes folder does not exist");

			return (FileSystem::getDirectories(Settings::setting("theme_location")));
		}

		/**
		 * @param null $folder
		 *
		 * @return string
		 */

		public function path($folder = null)
		{

			return (FileSystem::separate(
				Settings::setting("theme_location"),
				$folder,
				Settings::setting("theme_info_file")
			));
		}

		/**
		 * @param array $values
		 *
		 * @return ThemeData
		 */

		public static function dataInstance($values)
		{

			return (new ThemeData($values));
		}
	}