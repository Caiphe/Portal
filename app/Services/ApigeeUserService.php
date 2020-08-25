<?php

namespace App\Services;

use App\Services\ApigeeService;

class ApigeeUserService
{
	/**
	 * Set up a user on Apigee.
	 * If one already exists get the developer id
	 *
	 * @param      array  $data   The data
	 *
	 * @return     array  The user array with Apigee developer id
	 */
	public static function setupUser(array $data): array
	{
		$apigeeDeveloper = ApigeeService::post('developers', [
			"email" => $data['email'],
			"firstName" => $data['first_name'],
			"lastName" => $data['last_name'],
			"userName" => $data['first_name'] . $data['last_name'],
		])->json();

		if (isset($apigeeDeveloper['code'])) {
			$apigeeDeveloper = ApigeeService::get('developers/' . $data['email']);
		}

		$data['developer_id'] = $apigeeDeveloper['developerId'];

		return $data;
	}
}
