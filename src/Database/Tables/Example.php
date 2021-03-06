<?php
	declare(strict_types=1);

	namespace Framework\Database\Tables;

	/**
	 * Lewis Lancaster 2016
	 *
	 * Class Example
	 *
	 * @package Framework\Database\Tables
	 */

	use Framework\Database\Table;

	/**
	 * Class Example
	 * @package Framework\Database\Tables
	 */
	class Example extends Table
	{

		/**
		 * Example showing how to get a column from the database.
		 *
		 * @param $exampleid
		 *
		 * @return array|null|static[]
		 */

		public function getExampleID($exampleid)
		{

			$array = [
				'exampleid' => $exampleid
			];

			$result = $this->getTable()->where($array)->get();

			return ($result->isEmpty()) ? null : $result[0];
		}
	}