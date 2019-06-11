<?php
	declare(strict_types=1);

	namespace Framework\Application\UtilitiesV2\Makers;

	use Framework\Application\UtilitiesV2\Conventions\FileData;
	use Framework\Application\UtilitiesV2\FileOperator;
	use Framework\Application\UtilitiesV2\Format;
	use Framework\Application;

	/**
	 * Class Page
	 * @package Framework\Application\UtilitiesV2\Makers
	 */
	class Page extends Base
	{

		/**
		 * @param FileData|null $template
		 */

		public function before(FileData $template = null): void
		{

			if ($template == null)
				$template = FileOperator::pathDataInstance("resources/templates/template_page.module");

			parent::before($template);
		}

		/**
		 * @return string
		 */

		public function filepath(): string
		{
			return( Application::globals()->CONTROLLER_FILEPATH );
		}

		/**
		 * @return string
		 */

		public function namespace(): string
		{
			return( Format::rc( Application::globals()->CONTROLLER_NAMESPACE ) );
		}
	}