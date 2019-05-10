<?php

	namespace Framework\Views\BaseClasses;

	/**
	 * Lewis Lancaster 2017
	 *
	 * Class Middleware
	 *
	 * @package Framework\Views\BaseClasses
	 */

	use Framework\Application\Render;
	use Framework\Application\Settings;

	class Middleware
	{

		/**
		 * Redirects the user to an error
		 *
		 * @param string $message
		 *
		 * @param string $path
		 */

		public function redirectError($message = '', $path = '')
		{

			if (Settings::setting('error_use_session'))
			{

				$_SESSION['error'] = $message;

				if ($path !== '')
				{

					if (empty(explode('/', $path)))
					{

						$_SESSION['error_page'] = explode('/', $path)[0];
					}
					else
					{

						if (substr($path, 0, 1) == '/')
						{

							$_SESSION['error_page'] = substr($path, 1);
						}
						else
						{

							$_SESSION['error_page'] = $path;
						}
					}
				}
				else
				{

					$_SESSION['error_page'] = $this->getCurrentPage();
				}

				if ($path !== '')
				{

					Render::redirect(Settings::setting('controller_index_root') . $path . '?error');

					exit;
				}
				else
				{

					Render::redirect(Settings::setting('controller_index_root') . $this->getCurrentPage() . '?error');

					exit;
				}
			}
			else
			{

				if ($path !== '')
				{

					Render::redirect(Settings::setting('controller_index_root') . $path . '?error=' . $message);

					exit;
				}
				else
				{

					Render::redirect(Settings::setting('controller_index_root') . $this->getCurrentPage() . '?error=' . $message);

					exit;
				}
			}
		}

		/**
		 * Redirects the user to a success
		 *
		 * @param string $path
		 */

		public function redirectSuccess($path = '')
		{

			if ($path !== '')
			{

				Render::redirect(Settings::setting('controller_index_root') . $path . "?success");
				exit;
			}

			Render::redirect(Settings::setting('controller_index_root') . $this->getCurrentPage() . '?success');
			exit;
		}

		/**
		 * Renders a page
		 *
		 * @param $file
		 *
		 * @param $data
		 *
		 * @param bool $obclean
		 */

		public function render($file, $data, $obclean = true)
		{

			if ($obclean == true)
			{

				ob_clean();
			}

			Render::view($file, $data);

			exit;
		}

		/**
		 * Redirects the user to a page
		 *
		 * @param $url
		 *
		 * @param bool $exit
		 */

		public function redirect($url, $exit = false)
		{

			Render::redirect($url);

			if ($exit)
			{

				exit;
			}
		}

		/**
		 * Gets the current page
		 *
		 * @return string
		 */

		public function getCurrentPage()
		{

			$page = $this->getPageSplat();

			if (empty($page))
			{

				return Settings::setting('controller_index_page');
			}

			if (empty(explode('?', $page[0])) == false)
			{

				return explode('?', $page[0])[0];
			}

			return $page[0];
		}

		/**
		 * Gets the entire path in the form of an array
		 *
		 * @return array
		 */

		private function getPageSplat()
		{

			return array_values(array_filter(explode('/', strip_tags($_SERVER['REQUEST_URI']))));
		}
	}