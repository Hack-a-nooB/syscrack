<?php
	declare(strict_types=1);

	namespace Framework\Syscrack\Game\Softwares;

	/**
	 * Lewis Lancaster 2017
	 *
	 * Class ForceUpload
	 *
	 * @package Framework\Syscrack\Game\Softwares
	 */

	use Framework\Syscrack\Game\Bases\BaseSoftware;
	use Framework\Syscrack\Game\Tool;

	/**
	 * Class ForceUpload
	 * @package Framework\Syscrack\Game\Softwares
	 */
	class ForceUpload extends BaseSoftware
	{

		/**
		 * The configuration of this Structure
		 *
		 * @return array
		 */

		public function configuration()
		{

			return [
				'uniquename' => 'forceupload',
				'extension' => '.admin',
				'type' => 'admin',
				'installable' => true,
				'executable' => true,
				'localexecuteonly' => true,
			];
		}

		/**
		 * @param null $userid
		 * @param null $sofwareid
		 * @param null $computerid
		 *
		 * @return Tool
		 */

		public function tool($userid = null, $sofwareid = null, $computerid = null): Tool
		{

			$tool = new Tool("Force Upload", "danger");
			$tool->admin();
			$tool->setAction('forceupload');
			$tool->addInput("softwareid", "localsoftwares");
			$tool->panel("danger");
			$tool->icon = "arrow-up";

			return ($tool);
		}
	}