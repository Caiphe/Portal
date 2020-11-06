<?php

namespace App\Services;

class KycService
{
	/**
	 * Determines if there is a KYC
	 *
	 * @param      array  $groups  The groups
	 *
	 * @return     bool   True if kyc, False otherwise.
	 */
	public function getKycList(array $groups): array
	{
		return array_filter($groups, fn ($group) => method_exists($this, $group));
	}
	/**
	 * Determines if there is a KYC
	 *
	 * @param      array  $groups  The groups
	 *
	 * @return     string   The method for the first kyc.
	 */
	public function getFirstKyc(array $groups): string
	{
		$method = '';

		foreach ($groups as $group) {
			if (!method_exists($this, $group)) continue;
			return $group;
		}

		return $method;
	}

	public function momo($app)
	{
		return [
			'route' => route('app.kyc', [$app->aid, 'momo']),
			'view' => 'templates.apps.kyc',
			'with' => [
				'pdf' => '/kyc/momo/kyc.pdf',
				'for' => 'momo'
			]
		];
	}
}
