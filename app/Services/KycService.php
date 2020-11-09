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
				'options' => $this->momoOptions($app->country_code),
				'group' => 'momo',
				'app' => $app
			]
		];
	}

	protected function momoOptions(string $country): array
	{
		return [
			"ug" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Uganda_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Limited company incoporated in uganda",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered & Certified Certificate of Incorporation</h3>",
								"value" => "Registered & Certified Certificate of Incorporation"
							],
							[
								"label" => "<h3>Copies of Certified Memorandum and Articles of Association.</h3>",
								"value" => "Copies of Certified Memorandum and Articles of Association"
							],
							[
								"label" => "<h3>Proof of Particulars of Directors and/ Secretary:</h3><p><i>Registered & Certified Form 7/20 &/8 OR Certified Annual Returns (of the previous year)</i></p>",
								"value" => "Proof of Particulars of Directors and/ Secretary: Registered & Certified Form 7/20 &/8 OR Certified Annual Returns (of the previous year)"
							],
							[
								"label" => "<h3>Proof of Shareholding:</h3><p>Registered & Certified Memorandum and Articles of Association,</p> <strong>OR</strong> <p><i>Registered & Certified Annual Returns of the previous year. OR Registered & Certified Return of Allotment. This is applicable to companies that are more than one year.</i></p>",
								"value" => "Proof of Shareholding: Registered & Certified Memorandum and Articles of Association, OR Registered & Certified Annual Returns of the previous year. OR Registered & Certified Return of Allotment. This is applicable to companies that are more than one year"
							],
							[
								"label" => "<h3>Trading License</h3><p>Or <strong>Permit to Operate</strong>(If Licensed under a different law):<i>If the entity is registered as an NGO, a certified copy of the permit to operate should be made available..</i></p>",
								"value" => "Trading License Or Permit to Operate (If Licensed under a different law): If the entity is registered as an NGO, a certified copy of the permit to operate should be made available"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>(Shareholders of >=25% shares, account Signatories & at least 2 IDs of Directors- one of which would have signed on the Mobile Money resolution): <i>Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</i></p>",
								"value" => "Identity Documents of Primary and related parties (Shareholders of >=25% shares, account Signatories & at least 2 IDs of Directors- one of which would have signed on the Mobile Money resolution): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Proof of Address</h3><p><i>Certified copy of company form A9/18, OR Certified Annual Returns of the previous year, OR Tenancy Agreement, or Government/Local Authority bills OR Recent Utility Bill (not older than 3 months)</i></p>",
								"value" => "Proof of Address. Certified copy of company form A9/18, OR Certified Annual Returns of the previous year, OR Tenancy Agreement, or Government/Local Authority bills OR Recent Utility Bill (not older than 3 months)"
							],
							[
								"label" => "<h3>Registered & Certified Board Resolution</h3><p><i>to Open Mobile money account and indicating who the authorized signatories to the wallet/account (s).</i></p>",
								"value" => "Registered & Certified Board Resolution to Open Mobile money account and indicating who the authorized signatories to the wallet/account (s)."
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]

						]
					],
					[
						"label" => "Limited companies incorporated outside uganda",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered & Certified Certificate of Incorporation</h3>",
								"value" => "Registered & Certified Certificate of Incorporation"
							],
							[
								"label" => "<h3>Certificate of registration</h3>",
								"value" => "Certificate of registration"
							],
							[
								"label" => "<h3>Proof of Particulars of Directors and/ Secretary/Trustees:</h3><p><i>Registered & Certified Form 7/20 &/8 (or their equivalent and /as issued by the URSB), OR Certified Annual Returns (of the previous year).</i></p>",
								"value" => "Proof of Particulars of Directors and/ Secretary/Trustees: Registered & Certified Form 7/20 &/8 (or their equivalent and /as issued by the URSB), OR Certified Annual Returns (of the previous year)"
							],
							[
								"label" => "<h3>Registered & Certified Memorandum and Articles of Association.<h3>",
								"value" => "Registered & Certified Memorandum and Articles of Association"
							],
							[
								"label" => "<h3>Proof of Shareholding:</h3><p>Registered & Certified Memorandum and Articles of Association,</p> <strong>OR</strong> <p><i>Registered & Certified Annual Returns of the previous year. OR Registered & Certified Return of Allotment. This is applicable to companies that are more than one year.</i></p>",
								"value" => "Proof of Shareholding: Registered & Certified Memorandum and Articles of Association, OR Registered & Certified Annual Returns of the previous year. OR Registered & Certified Return of Allotment. This is applicable to companies that are more than one year"
							],
							[
								"label" => "<h3>Trading License</h3><p>Or <strong>Permit to Operate</strong>(If Licensed under a different law): <i>If the entity is registered as an NGO, a certified copy of the permit to operate should be made available..</i></p>",
								"value" => "Trading License Or Permit to Operate (If Licensed under a different law): If the entity is registered as an NGO, a certified copy of the permit to operate should be made available"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>(Shareholders of >=25% shares, account Signatories & at least 2 IDs of Directors- one of which would have signed on the Mobile Money resolution): <i>Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</i></p>",
								"value" => "Identity Documents of Primary and related parties (Shareholders of >=25% shares, account Signatories & at least 2 IDs of Directors- one of which would have signed on the Mobile Money resolution): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Proof of Address</h3><p><i>Certified copy of company form A9/18, OR Certified Annual Returns of the previous year, OR Tenancy Agreement, or Government/Local Authority bills OR Recent Utility Bill (not older than 3 months)</i></p>",
								"value" => "Proof of Address. Certified copy of company form A9/18, OR Certified Annual Returns of the previous year, OR Tenancy Agreement, or Government/Local Authority bills OR Recent Utility Bill (not older than 3 months)"
							],
							[
								"label" => "<h3>Registered & Certified Board Resolution</h3><p><i>to Open Mobile money account and indicating who the authorized signatories to the wallet/account (s).</i></p>",
								"value" => "Registered & Certified Board Resolution to Open Mobile money account and indicating who the authorized signatories to the wallet/account (s)."
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Saccos/unions/clubs/associations",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered and Certified certificate of registration</h3>",
								"value" => "Registered and Certified certificate of registration"
							],
							[
								"label" => "<h3>Registered and Certified Constitution</h3><p>or <strong>Registered and Certified By Laws</strong> <i>(Or the documents can be registered by URSB)</i></p>",
								"value" => "Registered and Certified Constitution or Registered and Certified By Laws (Or the documents can be registered by URSB)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p>(At least 2 Board/Executive Committee Members & Authorized Account Signatories): <i>Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees</i></p>",
								"value" => "Identity Documents of Primary and Related Parties (At least 2 Board/Executive Committee Members & Authorized Account Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate</h3>",
								"value" => "Tax Identification Number (TIN), or <strong>Tax Exemption Certificate"
							],
							[
								"label" => "<h3>Registered & Certified/ document registered by URSB</h3><p>to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating who the authorized signatories are to the mobile money account/wallet.</p>",
								"value" => "Registered & Certified/ document registered by URSB to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating who the authorized signatories are to the mobile money account/wallet."
							],
							[
								"label" => "<h3>Registered & Certified Resolution/ document registered by URSB;</h3><p> <i>Authorization to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating the authorized Mobile Money account/ wallet signatories as well.</i></p>",
								"value" => "Registered & Certified Resolution/ document registered by URSB; Authorization to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating the authorized Mobile Money account/ wallet signatories as well"
							],
							[
								"label" => "<h3>Registered Resolution appointing the Executive/Management committee</h3><p> <i>Indicating valid period in office. The registration shall be by URSB or the registering body for the respective business entity.</i></p>",
								"value" => "Registered Resolution appointing the Executive/Management committee, indicating valid period in office. The registration shall be by URSB or the registering body for the respective business entity"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p> <i>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address.</i></p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address"
							]
						]
					],
					[
						"label" => "Partnership",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified and Registered Certificate of Business Name Registration</h3>",
								"value" => "Certified and Registered Certificate of Business Name Registration"
							],
							[
								"label" => "<h3>Certified and Registered copy of Partnership Deed</h3>",
								"value" => "Certified and Registered copy of Partnership Deed"
							],
							[
								"label" => "<h3>Certified and Registered Statement of Particulars pursuant to the registration business.</h3>",
								"value" => "Certified and Registered Statement of Particulars pursuant to the registration business"
							],
							[
								"label" => "<h3>Certified and Registered Board Resolution</h3><p>to Open Mobile money account and indicating the authorized signatories to the account (s)</p>",
								"value" => "Certified and Registered Board Resolution to Open Mobile money account and indicating the authorized signatories to the account (s)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>partners, Account Signatories (National ID front and back (for nationals) OR Passport (for Foreigners also taking a copy of their entry VISA, and/or Work Permit) OR Refugee ID and Refugee attestations (for refugees) (Partner Ids as listed on the statement of particulars)</p>",
								"value" => "Identity Documents of Primary and related parties, partners, Account Signatories (National ID front and back (for nationals) OR Passport (for Foreigners also taking a copy of their entry VISA, and/or Work Permit) OR Refugee ID and Refugee attestations (for refugees) (Partner Ids as listed on the statement of particulars)"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or Tax Exemption Certificate</p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							],
							[
								"label" => "<h3>Trading License.</h3>",
								"value" => "Trading License"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Government / Local Authority Bills / LC Letter Bank Statement bearing business entity address. Statement of particulars</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Government / Local Authority Bills / LC Letter Bank Statement bearing business entity address. Statement of particulars"
							]
						]
					],
					[
						"label" => "Sole proprietor",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered and Certified Business Name Registration certificate.</h3>",
								"value" => "Registered and Certified Business Name Registration certificate"
							],
							[
								"label" => "<h3>Registered and Certified Statement of particulars pursuant to the registration business.</h3>",
								"value" => "Registered and Certified Statement of particulars pursuant to the registration business"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>Account Signatories (National ID (front and back) (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees).</p>",
								"value" => "Identity Documents of Primary and related parties, Account Signatories (National ID (front and back) (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees)."
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address. Statement of particulars</p>",
								"value" => "Proof of Address of the business name entity Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address. Statement of particulars"
							],
							[
								"label" => "<h3>Letter Applying for Mobile Money Service</h3><p>by the Business Owner or Authorized parties and indicating the authorized mobile money account /wallet signatories</p>",
								"value" => "Letter Applying for Mobile Money Service by the Business Owner or Authorized parties and indicating the authorized mobile money account /wallet signatories"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Embassies",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter from their Head of Mission</h3><p>requesting to Open Mobile money account</p>",
								"value" => "Letter from their Head of Mission requesting to Open Mobile money account"
							],
							[
								"label" => "<h3>A copy of a Letter of accreditation from the Ministry of Foreign Affairs </h3>",
								"value" => "A copy of a Letter of accreditation from the Ministry of Foreign Affairs"
							],
							[
								"label" => "<h3>A copy of the Embassy's Home Page</h3>",
								"value" => "A copy of the Embassy's Home Page"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>Head of Mission, Account Signatories (National ID front and back (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)</p>",
								"value" => "Identity Documents of Primary and related parties. Head of Mission, Account Signatories (National ID front and back (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Copy of Tenancy Agreement</p>",
								"value" => "Proof of Address of the business name entity. Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Copy of Tenancy Agreement"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>, or Tax Exemption Certificate</strong>/ <strong>any oer evidence of exemption</p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate any other evidence of exemption"
							]
						]
					],
					[
						"label" => "Non governmental organizations",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified copy of Certificate of Registration:</h3><p>Note: Effective 2016, registration is done by URSB and the NGO Board issues the License to Operate (Attach certified copies as applicable)</p>",
								"value" => "Certified copy of Certificate of Registration: Note: Effective 2016, registration is done by URSB and the NGO Board issues the License to Operate (Attach certified copies as applicable)"
							],
							[
								"label" => "<h3>Certified copy of Permit to operate as an NGO:</h3><p>Form C, Regulation 7 by the NGO Board Secretariat</p>",
								"value" => "Certified copy of Permit to operate as an NGO: Form C, Regulation 7 by the NGO Board Secretariat"
							],
							[
								"label" => "<h3>Registered and Certified Constitution/ By Laws</h3><p>by the NGO Board Secretariat, (Or Document registration by URSB for any of the above documents that will be submitted.)</p>",
								"value" => "Registered and Certified Constitution/ By Laws by the NGO Board Secretariat, (Or Document registration by URSB for any of the above documents that will be submitted.)"
							],
							[
								"label" => "<h3>A resolution</h3><p>indicating the current <strong>Board members/Executive committee</strong> & period of validity in office, Registered and certified by founding body or document registration by URSB.</p>",
								"value" => "A resolution indicating the current Board members/Executive committee & period of validity in office, Registered and certified by founding body or document registration by URSB."
							],
							[
								"label" => "<h3>Registered & Certified/ document registered by URSB</h3><p>to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating who the authorized signatories are to the mobile money account/wallet.</p>",
								"value" => "Registered & Certified/ document registered by URSB to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating who the authorized signatories are to the mobile money account/wallet."
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>for at least 2 Board/Executive committee Members and or Account Authorized Signatories ((National ID (front and back) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees).</p>",
								"value" => "Identity Documents of Primary and related parties for at least 2 Board/Executive committee Members and or Account Authorized Signatories ((National ID (front and back) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees)."
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills/ LC Letter Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills/ LC Letter Bank Statement bearing business entity address."
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Ministries/public sector/government accounts/parastatal",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter requesting for opening of a mobile money account with MTN Uganda</h3><p>signed by the Town Clerk for Municipalities, Divisions and Town Councils while for District Local Governments, the letter must be signed by the Chief Administrative Officer (CAO) and indicating the authorized signatories.</p>",
								"value" => "Letter requesting for opening of a mobile money account with MTN Uganda; signed by the Town Clerk for Municipalities, Divisions and Town Councils while for District Local Governments, the letter must be signed by the Chief Administrative Officer (CAO) and indicating the authorized signatories."
							],
							[
								"label" => "<h3>Attach copy of <strong>the page that brings the government body into force</strong>. Or a copy of the Website home page of the government entity.</h3>",
								"value" => "Attach copy of the page that brings the government body into force. Or a copy of the Website home page of the government entity."
							],
							[
								"label" => "<h3>Letter of authorization from the Accountant General</h3><p>Ministry of Finance Planning and Economic Development allowing opening of the Mobile Money account as required by Section 33 of the Public Finance Management Act.</p>",
								"value" => "Letter of authorization from the Accountant General- Ministry of Finance Planning and Economic Development allowing opening of the Mobile Money account as required by Section 33 of the Public Finance Management Act."
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p>(Account Authorized Signatories):Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners</p>",
								"value" => "Identity Documents of Primary and Related Parties (Account Authorized Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Bank Statement bearing business entity address."
							]
						]
					],
					[
						"label" => "Existing momo partner",
						"kycChecklist" => [[
							"label" => "<h3>MTN Open API integration Request Letter signed by at least two authorized signatories</h3><p></p>",
							"value" => "Identity Documents of Primary and related parties(Directors & Authorized Account Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
						]]
					],
					[
						"label" => "Schools, education institutions",
						"kycChecklist" => [
							[
								"label" => "<h3>Certificate of Registration</h3><p><i>(Business Name)</i>:Depending on the registration details e.g. Limited Company, Partnership etc. then the related/specified documents above should be attached</p>",
								"value" => "Certificate of Registration (Business Name): Depending on the registration details e.g. Limited Company, Partnership etc. then the related/specified documents above should be attached"
							],
							[
								"label" => "<h3>Copy of the School Registration Certificate</h3><p>(Issued by Ministry of Education & Sports - M.o.E.S):This will be submitted if a school has been in existence for a period that is more than two years.</p>",
								"value" => "Copy of the School Registration Certificate (Issued by Ministry of Education & Sports - M.o.E.S): This will be submitted if a school has been in existence for a period that is more than two years."
							],
							[
								"label" => "<h3>Copy of the License from the Ministry of Education:</h3><p>This will be submitted if a school is in existence for a period that is less than two years ONLY.</p>",
								"value" => "Copy of the License from the Ministry of Education: This will be submitted if a school is in existence for a period that is less than two years ONLY."
							],
							[
								"label" => "<h3>Proof of ownership</h3><p>about a given business type, as detailed in 1 above.</p>",
								"value" => "Proof of ownership, about a given business type, as detailed in 1 above"
							],
							[
								"label" => "<h3>Resolution/Letter requesting /applying for Use of Mobile Money</h3><p>signed by the authorized entities as referenced in the founding documents, holding respective offices that are mandated to open accounts on behalf of the business entity and indicating who the authorized signatories will be for the Mobile Money wallet/ account (s).</p>",
								"value" => "Resolution/Letter requesting /applying for Use of Mobile Money signed by the authorized entities as referenced in the founding documents, holding respective offices that are mandated to open accounts on behalf of the business entity and indicating who the authorized signatories will be for the Mobile Money wallet/ account (s)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties </h3><p>(Shares >=25%, Directors, Account Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Shares >=25%, Directors, Account Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption.</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption"
							]
						]
					],
					[
						"label" => "Religious worship, churches, mosques etc",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified Copy of Certificate of Registration of parent</h3><p><i>(Company or its equivalent)</i>:Depending on the registration details, for example a Limited company and Partnership, then the related documents will be attached such as a Registration Certificate from the parent body, for example but not limited to the Church and Mosque</p>",
								"value" => "Certified Copy of Certificate of Registration of parent(Company or its equivalent): Depending on the registration details, for example a Limited company and Partnership, then the related documents will be attached such as a Registration Certificate from the parent body, for example but not limited to the Church and Mosque"
							],
							[
								"label" => "<h3>An Introduction/recommendation letter</h3><p>from the Parent/ Mother entity</p>",
								"value" => "An Introduction/recommendation letter from the Parent/ Mother entity"
							],
							[
								"label" => "<h3>A recommendation letter from respective Secretariat:</h3><p>Indicate the name of the secretariat For Non-traditional religious faith, consider the license from URSB (where applicable).</p>",
								"value" => "A recommendation letter from respective Secretariat: Indicate the name of the secretariat For Non-traditional religious faith, consider the license from URSB (where applicable)"
							],
							[
								"label" => "<h3>Registered/Certified resolution/Letter for use of Mobile Money</h3><p>Services signed by the authorized persons.</p>",
								"value" => "Registered/Certified resolution/Letter for use of Mobile Money Services signed by the authorized persons"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p>(Directors/Nominated Administrators):Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Directors/Nominated Administrators): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Authorization letter to open mobile money from the Vicar/Secretariat</h3><p>of the religious faith for the mainstream churches/mosques and or registration documents of the URSB depending on the mode of registration, and indicating who the Authorized signatories are to the mobile money wallet / account</p>",
								"value" => "Authorization letter to open mobile money from the Vicar/Secretariat of the religious faith for the mainstream churches/mosques and or registration documents of the URSB depending on the mode of registration, and indicating who the Authorized signatories are to the mobile money wallet / account"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Letter from Local Council Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Letter from Local Council Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number</h3><p>(TIN), or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Cultural institutions e.g king/chiefdoms & any related activity projects",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter from the Kingdom Prime Minister requesting for Mobile Money Services</h3><p>expressing the Kingdoms mandate to apply for the services and stating the authorized individuals to handle matters related to the service on behalf of the Kingdom.</p>",
								"value" => "Letter from the Kingdom Prime Minister requesting for Mobile Money Services expressing the Kingdoms mandate to apply for the services and stating the authorized individuals to handle matters related to the service on behalf of the Kingdom"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p><i> (Authorized Signatories/Nominated Administrators)</i>:Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Authorized Signatories/Nominated Administrators): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council, Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council, Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number</h3><p>(TIN), or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Trustees",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered & Certified Board Resolution/ Authorization to Open Mobile money account</h3><p>and indicating the authorized signatories.</p>",
								"value" => "Registered & Certified Board Resolution/ Authorization to Open Mobile money account and indicating the authorized signatories"
							],
							[
								"label" => "<h3>Registered & Certified Certificate for registration</h3>",
								"value" => "Registered & Certified Certificate for registration"
							],
							[
								"label" => "<h3>Registered & Certified copy of the Trust Deed</h3><p>and/ Constitution/ certified copy of the By Laws.</p>",
								"value" => "Registered & Certified copy of the Trust Deed, and/ Constitution/ certified copy of the By Laws"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p><i> (Director/Trustees/Administrators, Authorized Signatories)</i>:Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Director/Trustees/Administrators, Authorized Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Registered & Certified Particulars of Trustees</h3>",
								"value" => "Registered & Certified Particulars of Trustees"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number</h3><p>(TIN), or <strong>Tax Exemption Certificate</strong> / any other evidence of exemption</p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate / any other evidence of exemption"
							]
						]
					],
					[
						"label" => "Political parties",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified copy of Registration Certificate from the Electoral Commission.</h3>",
								"value" => "Certified copy of Registration Certificate from the Electoral Commission"
							],
							[
								"label" => "<h3>A certified copy of the party Constitution</h3><p>that is filed with the Electoral Commission.</p>",
								"value" => "A certified copy of the party Constitution that is filed with the Electoral Commission"
							],
							[
								"label" => "<h3>A Party Resolution to open a Mobile Money Account with MTN:</h3><p>The Resolution must have been filed with the Registrar of Documents- URSB, indicating the authorized signatories to the Mobile Money account(s)</p>",
								"value" => "A Party Resolution to open a Mobile Money Account with MTN: The Resolution must have been filed with the Registrar of Documents- URSB, indicating the authorized signatories to the Mobile Money account(s)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p><i> (the Party President, Secretary General, Treasurer of the party and Account Signatories)</i>:Acceptable IDs are: National ID (front and back) for Ugandan Nationals</p>",
								"value" => "Identity Documents of Primary and Related Parties (the Party President, Secretary General, Treasurer of the party and Account Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Copy of the Tenancy Agreement Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Copy of the Tenancy Agreement Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption.</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption"
							]
						]
					]
				]
			],
			"gh" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Ghana_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Sole proprietorship",
						"kycChecklist" => [
							[
								"label" => "<h3>Open API Application Form A (Individual / Sole Proprietorship)</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Registered & Certified Certificate of Incorporation</h3>",
								"value" => "Registered & Certified Certificate of Incorporation"
							],
							[
								"label" => "<h3>Certitficate of Commencement</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Form A</h3><p><i>Proof of ownership</i></p>",
								"value" => "Proof of ownership"
							],
							[
								"label" => "<h3>License/Permit to operate</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Valid ID of Ultimate Beneficiary Owner</h3><p><i>Highest Share Holder / Owner Of Business</i></p>",
								"value" => "Highest Share Holder / Owner Of Business"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate</h3><p><i>Highest Share Holder / Owner Of Business</i></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Partnership",
						"kycChecklist" => [
							[
								"label" => "<h3>Open API Application Form B (Corporate)</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Registered & Certified Certificate of Incorporation</h3>",
								"value" => "Registered & Certified Certificate of Incorporation"
							],
							[
								"label" => "<h3>Certitficate of Commencement</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Form A</h3><p><i>Proof of ownership</i></p>",
								"value" => "Proof of ownership"
							],
							[
								"label" => "<h3>Trading Licence/Permit to Operate</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Identification documents of at least 2 Directors</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Valid ID of Ultimate Beneficiary Owner</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Proof of address of Ultimate Beneficiary Owner and 2 Directors</h3><p><i>Highest Share Holder / Owner Of Business</i></p>",
								"value" => "Highest Share Holder / Owner Of Business"
							],
							[
								"label" => "<h3>TIN of Ultimate Beneficiary Owner</h3>",
								"value" => ""
							]
						]
					],
					[
						"label" => "Company limited by shares",
						"kycChecklist" => [
							[
								"label" => "<h3>Open API Application Form B (Corporate)</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Certificate of Incorporation</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Certitficate of Commencement</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Form 3</h3><p><i>Proof of shareholding</i></p>",
								"value" => "Proof of shareholding "
							],
							[
								"label" => "<h3>Trading Licence/Permit to Operate</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Identification documents of at least 2 Directors</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Valid ID of Ultimate Beneficiary Owner</h3><p><i>Highest Share Holder / Owner Of Business</i></p>",
								"value" => "Valid ID of Ultimate Beneficiary Owner"
							],
							[
								"label" => "<h3>Proof of address of Ultimate Beneficiary Owner and 2 Directors</h3><p><i>Highest Share Holder / Owner Of Business</i></p>",
								"value" => ""
							],
							[
								"label" => "<h3>TIN of Ultimate Beneficiary Owner</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Completed AML Questionaire</h3><p><i>Fintechs/Financial Institutions/RSPs/Gaming Businesses</i></p>",
								"value" => "Fintechs/Financial Institutions/RSPs/Gaming Businesses"
							],
							[
								"label" => "<h3>AML Policy Document Approved by Board of Directors </h3><p><i>Fintechs/Financial Institutions/RSPs/Gaming Businesses</i></p>",
								"value" => "Fintechs/Financial Institutions/RSPs/Gaming Businesses"
							]
						]
					],
					[
						"label" => "Company limited by guarantee",
						"kycChecklist" => [
							[
								"label" => "<h3>Open API Application Form B (Corporate)</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Certificate of Incorporation</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Certitficate of Commencement</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Form 3</h3><p><i>Proof of shareholding</i></p>",
								"value" => "Proof of shareholding "
							],
							[
								"label" => "<h3>Trading Licence/Permit to Operate</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Identification documents of at least 2 Directors</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Valid ID of Ultimate Beneficiary Owner</h3><p><i>Highest Share Holder / Owner Of Business</i></p>",
								"value" => "Highest Share Holder / Owner Of Business"
							],
							[
								"label" => "<h3>Proof of address of Ultimate Beneficiary Owner and 2 Directors</h3><p><i>Highest Share Holder / Owner Of Business</i></p>",
								"value" => ""
							],
							[
								"label" => "<h3>TIN of Ultimate Beneficiary Owner</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Completed AML Questionaire</h3><p><i>Fintechs/Financial Institutions/Remittance Service Providers/Gaming Businesses</i></p>",
								"value" => "Fintechs/Financial Institutions/Remittance Service Providers/Gaming Businesses"
							],
							[
								"label" => "<h3>AML Policy Document Approved by Board of Directors</h3><p><i>Fintechs/Financial Institutions/Remittance Service Providers/Gaming Businesses</i></p>",
								"value" => "Fintechs/Financial Institutions/Remittance Service Providers/Gaming Businesses"
							]
						]
					],
					[
						"label" => "Government agencies / ministries",
						"kycChecklist" => [
							[
								"label" => "<h3>Open API Application Form B (Corporate)</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Letter signed by Minister in Charge</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Identification documents of at least 2 Directors</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Proof of address of 2 Directors</h3>",
								"value" => ""
							]
						]
					],
					[
						"label" => "International organizations",
						"kycChecklist" => [
							[
								"label" => "<h3>Open API Application Form B (Corporate)</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Letter signed by Head of Organization in Ghana</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Identification documents of at least 2 Directors</h3>",
								"value" => ""
							],
							[
								"label" => "<h3>Proof of address of 2 Directors</h3>",
								"value" => ""
							]
						]
					]
				]
			],
			"ci" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/IvoryCoast_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Non-governmental organization (ngo)",
						"kycChecklist" => [
							[
								"label" => "<h3>Contrat - Depending on the Product</h3><p><i>Fill and sign contract and then upload them again in the field below</i></p>",
								"value" => "Contrat - Depending on the Product. Fill and sign contract and then upload them again in the field below"
							],
							[
								"label" => "<h3>Creation Decree</h3><p><i>Upload company creation decree</i></p>",
								"value" => "Creation Decree. Upload company creation decree"
							],
							[
								"label" => "<h3>Statutes of the NGO</h3><p><i>Upload Statutes Non-governmental organization</i></p>",
								"value" => "Statutes of the NGO. Upload Statutes Non-governmental organization"
							],
							[
								"label" => "<h3>Copy of main responsible National ID Card</h3><p><i>Upload copy of main responsible National ID Card (CNI)</i></p>",
								"value" => "Copy of main responsible National ID Card. Upload copy of main responsible National ID Card (CNI)"
							],
							[
								"label" => "<h3>test cases document</h3><p><i>Upload signed test cases document </i></p>",
								"value" => "test cases document. Upload signed test cases document"
							]
						]
					],
					[
						"label" => "Private company",
						"kycChecklist" => [
							[
								"label" => "<h3>Contrat - Depending on the Product</h3><p><i>Fill and sign contract and then upload them again in the field below</i></p>",
								"value" => "Contrat - Depending on the Product. Fill and sign contract and then upload them again in the field below"
							],
							[
								"label" => "<h3>Trade Register (RC) </h3><p><i>Upload trade register</i></p>",
								"value" => "Trade Register (RC). Upload trade register"
							],
							[
								"label" => "<h3>Copy of main responsible National ID Card </h3><p><i>Upload main responsible National ID card(CNI)</i></p>",
								"value" => "Copy of main responsible National ID Card. Upload main responsible National ID card(CNI)"
							],
							[
								"label" => "<h3>test cases document</h3><p><i>Upload signed test cases document signed</i></p>",
								"value" => "test cases document. Upload signed test cases document signed"
							],
							[
								"label" => "<h3>Tax declaration (DFE) (Optional)</h3><p><i>Upload Tax declaration</i></p>",
								"value" => "Tax declaration (DFE) (Optional). Upload Tax declaration",
								"required" => false
							]
						]
					],
					[
						"label" => "Public and government",
						"kycChecklist" => [
							[
								"label" => "<h3>Contrat - Depending on the Product</h3><p><i>Fill and sign contract and then upload them again in the field below</i></p>",
								"value" => "Contrat - Depending on the Product. Fill and sign contract and then upload them again in the field below"
							],
							[
								"label" => "<h3>Creation Decree</h3><p><i>Upload company creation decree</i></p>",
								"value" => "Creation Decree. Upload company creation decree"
							],
							[
								"label" => "<h3>Statutes of the NGO </h3><p><i>Upload Statutes Non-governmental organization</i></p>",
								"value" => "Statutes of the NGO. Upload Statutes Non-governmental organization"
							],
							[
								"label" => "<h3>Copy of main responsible National ID Card</h3><p><i>Upload main responsible National ID card(CNI)</i></p>",
								"value" => "Copy of main responsible National ID Card. Upload main responsible National ID card(CNI)"
							],
							[
								"label" => "<h3>test cases document</h3><p><i>Upload test cases document</i></p>",
								"value" => "test cases document. Upload test cases document"
							]
						]
					]
				]
			],
			"zm" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Zambia_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Partnership",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified and Registered Certificate of Business Name Registration</h3>",
								"value" => "Certified and Registered Certificate of Business Name Registration"
							],
							[
								"label" => "<h3>Certified and Registered copy of Partnership Deed</h3>",
								"value" => "Certified and Registered copy of Partnership Deed"
							],
							[
								"label" => "<h3>Certified and Registered Statement of Particulars pursuant to the registration business.</h3>",
								"value" => "Certified and Registered Statement of Particulars pursuant to the registration business"
							],
							[
								"label" => "<h3>Certified and Registered Board Resolution</h3><p>to Open Mobile money account and indicating the authorized signatories to the account (s)</p>",
								"value" => "Certified and Registered Board Resolution to Open Mobile money account and indicating the authorized signatories to the account (s)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>partners, Account Signatories (National ID front and back (for nationals) OR Passport (for Foreigners also taking a copy of their entry VISA, and/or Work Permit) OR Refugee ID and Refugee attestations (for refugees) (Partner Ids as listed on the statement of particulars)</p>",
								"value" => "Identity Documents of Primary and related parties, partners, Account Signatories (National ID front and back (for nationals) OR Passport (for Foreigners also taking a copy of their entry VISA, and/or Work Permit) OR Refugee ID and Refugee attestations (for refugees) (Partner Ids as listed on the statement of particulars)"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or Tax Exemption Certificate</p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							],
							[
								"label" => "<h3>Trading License.</h3>",
								"value" => "Trading License"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Government / Local Authority Bills / LC Letter Bank Statement bearing business entity address. Statement of particulars</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Government / Local Authority Bills / LC Letter Bank Statement bearing business entity address. Statement of particulars"
							]
						]
					],
					[
						"label" => "Sole proprietorship",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered and Certified Business Name Registration certificate.</h3>",
								"value" => "Registered and Certified Business Name Registration certificate"
							],
							[
								"label" => "<h3>Registered and Certified Statement of particulars pursuant to the registration business.</h3>",
								"value" => "Registered and Certified Statement of particulars pursuant to the registration business"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>Account Signatories (National ID (front and back) (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees).</p>",
								"value" => "Identity Documents of Primary and related parties, Account Signatories (National ID (front and back) (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees)."
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address. Statement of particulars</p>",
								"value" => "Proof of Address of the business name entity Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address. Statement of particulars"
							],
							[
								"label" => "<h3>Letter Applying for Mobile Money Service</h3><p>by the Business Owner or Authorized parties and indicating the authorized mobile money account /wallet signatories</p>",
								"value" => "Letter Applying for Mobile Money Service by the Business Owner or Authorized parties and indicating the authorized mobile money account /wallet signatories"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Company limited by shares",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter from their Head of Mission</h3><p>requesting to Open Mobile money account</p>",
								"value" => "Letter from their Head of Mission requesting to Open Mobile money account"
							],
							[
								"label" => "<h3>A copy of a <strong>Letter of accreditation from the Ministry of Foreign Affairs </strong></h3>",
								"value" => "A copy of a Letter of accreditation from the Ministry of Foreign Affairs"
							],
							[
								"label" => "<h3>A copy of the <strong>Embassy's Home Page</strong></h3>",
								"value" => "A copy of the Embassy's Home Page"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>Head of Mission, Account Signatories (National ID front and back (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)</p>",
								"value" => "Identity Documents of Primary and related parties. Head of Mission, Account Signatories (National ID front and back (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Copy of Tenancy Agreement</p>",
								"value" => "Proof of Address of the business name entity. Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Copy of Tenancy Agreement"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>, or Tax Exemption Certificate</strong>/ <strong>any oer evidence of exemption</p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate any other evidence of exemption"
							]
						]
					],
					[
						"label" => "Company limited by guarantee",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified copy of Certificate of Registration:</h3><p>Note: Effective 2016, registration is done by URSB and the NGO Board issues the License to Operate (Attach certified copies as applicable)</p>",
								"value" => "Certified copy of Certificate of Registration: Note: Effective 2016, registration is done by URSB and the NGO Board issues the License to Operate (Attach certified copies as applicable)"
							],
							[
								"label" => "<h3>Certified copy of Permit to operate as an NGO:</h3><p>Form C, Regulation 7 by the NGO Board Secretariat</p>",
								"value" => "Certified copy of Permit to operate as an NGO: Form C, Regulation 7 by the NGO Board Secretariat"
							],
							[
								"label" => "<h3>Registered and Certified Constitution/ By Laws</h3><p>by the NGO Board Secretariat, (Or Document registration by URSB for any of the above documents that will be submitted.)</p>",
								"value" => "Registered and Certified Constitution/ By Laws by the NGO Board Secretariat, (Or Document registration by URSB for any of the above documents that will be submitted.)"
							],
							[
								"label" => "<h3>A resolution</h3><p>indicating the current <strong>Board members/Executive committee</strong> & period of validity in office, Registered and certified by founding body or document registration by URSB.</p>",
								"value" => "A resolution indicating the current Board members/Executive committee & period of validity in office, Registered and certified by founding body or document registration by URSB."
							],
							[
								"label" => "<h3>Registered & Certified/ document registered by URSB</h3><p>to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating who the authorized signatories are to the mobile money account/wallet.</p>",
								"value" => "Registered & Certified/ document registered by URSB to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating who the authorized signatories are to the mobile money account/wallet."
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>for at least 2 Board/Executive committee Members and or Account Authorized Signatories ((National ID (front and back) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees).</p>",
								"value" => "Identity Documents of Primary and related parties for at least 2 Board/Executive committee Members and or Account Authorized Signatories ((National ID (front and back) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees)."
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills/ LC Letter Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills/ LC Letter Bank Statement bearing business entity address."
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Government agencies / ministries",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter requesting for opening of a mobile money account with MTN Zambia</h3><p>signed by the Town Clerk for Municipalities, Divisions and Town Councils while for District Local Governments, the letter must be signed by the Chief Administrative Officer (CAO) and indicating the authorized signatories.</p>",
								"value" => "Letter requesting for opening of a mobile money account with MTN Zambia; signed by the Town Clerk for Municipalities, Divisions and Town Councils while for District Local Governments, the letter must be signed by the Chief Administrative Officer (CAO) and indicating the authorized signatories."
							],
							[
								"label" => "<h3>Attach copy of <strong>the page that brings the government body into force</strong>. Or a copy of the Website home page of the government entity.<h3>",
								"value" => "Attach copy of the page that brings the government body into force. Or a copy of the Website home page of the government entity."
							],
							[
								"label" => "<h3>Letter of authorization from the Accountant General</h3><p>Ministry of Finance Planning and Economic Development allowing opening of the Mobile Money account as required by Section 33 of the Public Finance Management Act.</p>",
								"value" => "Letter of authorization from the Accountant General- Ministry of Finance Planning and Economic Development allowing opening of the Mobile Money account as required by Section 33 of the Public Finance Management Act."
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p>(Account Authorized Signatories):Acceptable IDs are: National ID (front and back) for Zambian Nationals, Passport & Entry VISA and/or Work Permit for Foreigners</p>",
								"value" => "Identity Documents of Primary and Related Parties (Account Authorized Signatories): Acceptable IDs are: National ID (front and back) for Zambian Nationals, Passport & Entry VISA and/or Work Permit for Foreigners"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Bank Statement bearing business entity address."
							]
						]
					],
				]
			],
			"cm" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Cameroon_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Limited company incoporated in cameroon",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered & Certified Certificate of Incorporation</h3>",
								"value" => "Registered & Certified Certificate of Incorporation"
							],
							[
								"label" => "<h3>Copies of Certified Memorandum and Articles of Association.</h3>",
								"value" => "Copies of Certified Memorandum and Articles of Association"
							],
							[
								"label" => "<h3>Proof of Particulars of Directors and/ Secretary:</h3><p><i>Registered & Certified Form 7/20 &/8 OR Certified Annual Returns (of the previous year)</i></p>",
								"value" => "Proof of Particulars of Directors and/ Secretary: Registered & Certified Form 7/20 &/8 OR Certified Annual Returns (of the previous year)"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Limited companies incorporated outside cameroon",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered & Certified Certificate of Incorporation</h3>",
								"value" => "Registered & Certified Certificate of Incorporation"
							],
							[
								"label" => "<h3>Certificate of registration</h3>",
								"value" => "Certificate of registration"
							],
							[
								"label" => "<h3>Proof of Particulars of Directors and/ Secretary/Trustees:</h3><p><i>Registered & Certified Form 7/20 &/8 (or their equivalent and /as issued by the URSB), OR Certified Annual Returns (of the previous year).</i></p>",
								"value" => "Proof of Particulars of Directors and/ Secretary/Trustees: Registered & Certified Form 7/20 &/8 (or their equivalent and /as issued by the URSB), OR Certified Annual Returns (of the previous year)"
							],
							[
								"label" => "<h3>Registered & Certified Memorandum and Articles of Association.<h3>",
								"value" => "Registered & Certified Memorandum and Articles of Association"
							],
							[
								"label" => "<h3>Proof of Shareholding:</h3><p>Registered & Certified Memorandum and Articles of Association,</p> <strong>OR</strong> <p><i>Registered & Certified Annual Returns of the previous year. OR Registered & Certified Return of Allotment. This is applicable to companies that are more than one year.</i></p>",
								"value" => "Proof of Shareholding: Registered & Certified Memorandum and Articles of Association, OR Registered & Certified Annual Returns of the previous year. OR Registered & Certified Return of Allotment. This is applicable to companies that are more than one year"
							],
							[
								"label" => "<h3>Trading License</h3><p>Or <strong>Permit to Operate</strong>(If Licensed under a different law): <i>If the entity is registered as an NGO, a certified copy of the permit to operate should be made available..</i></p>",
								"value" => "Trading License Or Permit to Operate (If Licensed under a different law): If the entity is registered as an NGO, a certified copy of the permit to operate should be made available"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>(Shareholders of >=25% shares, account Signatories & at least 2 IDs of Directors- one of which would have signed on the Mobile Money resolution):<i>Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</i></p>",
								"value" => "Identity Documents of Primary and related parties (Shareholders of >=25% shares, account Signatories & at least 2 IDs of Directors- one of which would have signed on the Mobile Money resolution): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Proof of Address</h3><p><i>Certified copy of company form A9/18, OR Certified Annual Returns of the previous year, OR Tenancy Agreement, or Government/Local Authority bills OR Recent Utility Bill (not older than 3 months)</i></p>",
								"value" => "Proof of Address. Certified copy of company form A9/18, OR Certified Annual Returns of the previous year, OR Tenancy Agreement, or Government/Local Authority bills OR Recent Utility Bill (not older than 3 months)"
							],
							[
								"label" => "<h3>Registered & Certified Board Resolution</h3><p><i>to Open Mobile money account and indicating who the authorized signatories to the wallet/account (s).</i></p>",
								"value" => "Registered & Certified Board Resolution to Open Mobile money account and indicating who the authorized signatories to the wallet/account (s)."
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Saccos/unions/clubs/associations",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered and Certified certificate of registration</h3>",
								"value" => "Registered and Certified certificate of registration"
							],
							[
								"label" => "<h3>Registered and Certified Constitution</h3><p>or <strong>Registered and Certified By Laws</strong> <i>(Or the documents can be registered by URSB)</i></p>",
								"value" => "Registered and Certified Constitution or Registered and Certified By Laws (Or the documents can be registered by URSB)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p>(At least 2 Board/Executive Committee Members & Authorized Account Signatories): <i>Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees</i></p>",
								"value" => "Identity Documents of Primary and Related Parties (At least 2 Board/Executive Committee Members & Authorized Account Signatories): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate</h3>",
								"value" => "Tax Identification Number (TIN), or <strong>Tax Exemption Certificate"
							],
							[
								"value" => "<strong>Registered & Certified Resolution/ document registered by URSB;</strong> <p> <i>Authorization to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating the authorized Mobile Money account/ wallet signatories as well.</i></p>",
								"value" => "Registered & Certified Resolution/ document registered by URSB; Authorization to Open Mobile money account signed by Authorized parties i.e. Board members/Executive Committee, indicating the authorized Mobile Money account/ wallet signatories as well"
							],
							[
								"label" => "<h3>Registered Resolution appointing the Executive/Management committee</h3><p><i>indicating valid period in office. The registration shall be by URSB or the registering body for the respective business entity.</i></p>",
								"value" => "Registered Resolution appointing the Executive/Management committee, indicating valid period in office. The registration shall be by URSB or the registering body for the respective business entity"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p> <i>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address.</i></p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address"
							]
						]
					],
					[
						"label" => "Partnership",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified and Registered Certificate of Business Name Registration</h3>",
								"value" => "Certified and Registered Certificate of Business Name Registration"
							],
							[
								"label" => "<h3>Certified and Registered copy of Partnership Deed</h3>",
								"value" => "Certified and Registered copy of Partnership Deed"
							],
							[
								"label" => "<h3>Certified and Registered Statement of Particulars pursuant to the registration business.</h3>",
								"value" => "Certified and Registered Statement of Particulars pursuant to the registration business"
							],
							[
								"label" => "<h3>Certified and Registered Board Resolution</h3><p>to Open Mobile money account and indicating the authorized signatories to the account (s)</p>",
								"value" => "Certified and Registered Board Resolution to Open Mobile money account and indicating the authorized signatories to the account (s)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>partners, Account Signatories (National ID front and back (for nationals) OR Passport (for Foreigners also taking a copy of their entry VISA, and/or Work Permit) OR Refugee ID and Refugee attestations (for refugees) (Partner Ids as listed on the statement of particulars)</p>",
								"value" => "Identity Documents of Primary and related parties, partners, Account Signatories (National ID front and back (for nationals) OR Passport (for Foreigners also taking a copy of their entry VISA, and/or Work Permit) OR Refugee ID and Refugee attestations (for refugees) (Partner Ids as listed on the statement of particulars)"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or Tax Exemption Certificate</p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							],
							[
								"label" => "<h3>Trading License.</h3>",
								"value" => "Trading License"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Government / Local Authority Bills / LC Letter Bank Statement bearing business entity address. Statement of particulars</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Government / Local Authority Bills / LC Letter Bank Statement bearing business entity address. Statement of particulars"
							]
						]
					],
					[
						"label" => "Sole proprietor",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered and Certified Business Name Registration certificate.</h3>",
								"value" => "Registered and Certified Business Name Registration certificate"
							],
							[
								"label" => "<h3>Registered and Certified Statement of particulars pursuant to the registration business.</h3>",
								"value" => "Registered and Certified Statement of particulars pursuant to the registration business"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>Account Signatories (National ID (front and back) (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees).</p>",
								"value" => "Identity Documents of Primary and related parties, Account Signatories (National ID (front and back) (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)) OR Refugee ID and Refugee attestations (for refugees)."
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address. Statement of particulars</p>",
								"value" => "Proof of Address of the business name entity Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address. Statement of particulars"
							],
							[
								"label" => "<h3>Letter Applying for Mobile Money Service</h3><p>by the Business Owner or Authorized parties and indicating the authorized mobile money account /wallet signatories</p>",
								"value" => "Letter Applying for Mobile Money Service by the Business Owner or Authorized parties and indicating the authorized mobile money account /wallet signatories"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN)</h3><p>or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Embassies",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter from their Head of Mission</h3><p>requesting to Open Mobile money account</p>",
								"value" => "Letter from their Head of Mission requesting to Open Mobile money account"
							],
							[
								"label" => "<h3>A copy of a <strong>Letter of accreditation from the Ministry of Foreign Affairs </strong></h3>",
								"value" => "A copy of a Letter of accreditation from the Ministry of Foreign Affairs"
							],
							[
								"label" => "<h3>A copy of the <strong>Embassy's Home Page</strong></h3>",
								"value" => "A copy of the Embassy's Home Page"
							],
							[
								"label" => "<h3>Identity Documents of Primary and related parties</h3><p>Head of Mission, Account Signatories (National ID front and back (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)</p>",
								"value" => "Identity Documents of Primary and related parties. Head of Mission, Account Signatories (National ID front and back (for nationals) OR Passport ((for Foreigners also taking a copy of their entry VISA, and/or Work Permit)"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Copy of Tenancy Agreement</p>",
								"value" => "Proof of Address of the business name entity. Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Copy of Tenancy Agreement"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate/ any other evidence of exemption</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate any other evidence of exemption"
							]
						]
					],
					[
						"label" => "Ministries/public sector/government accounts/parastatal",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter requesting for opening of a mobile money account with MTN Cameroon</h3><p>signed by the Town Clerk for Municipalities, Divisions and Town Councils while for District Local Governments, the letter must be signed by the Chief Administrative Officer (CAO) and indicating the authorized signatories.</p>",
								"value" => "Letter requesting for opening of a mobile money account with MTN Cameroon; signed by the Town Clerk for Municipalities, Divisions and Town Councils while for District Local Governments, the letter must be signed by the Chief Administrative Officer (CAO) and indicating the authorized signatories."
							],
							[
								"label" => "<h3>Attach copy of <strong>the page that brings the government body into force</strong>. Or a copy of the Website home page of the government entity.</h3>",
								"value" => "Attach copy of the page that brings the government body into force. Or a copy of the Website home page of the government entity."
							],
							[
								"label" => "<h3>Letter of authorization from the Accountant General</h3><p>Ministry of Finance Planning and Economic Development allowing opening of the Mobile Money account as required by Section 33 of the Public Finance Management Act.</p>",
								"value" => "Letter of authorization from the Accountant General- Ministry of Finance Planning and Economic Development allowing opening of the Mobile Money account as required by Section 33 of the Public Finance Management Act."
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p>(Account Authorized Signatories):Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners</p>",
								"value" => "Identity Documents of Primary and Related Parties (Account Authorized Signatories): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Bank Statement bearing business entity address."
							]
						]
					],
					[
						"label" => "Schools, education institutions",
						"kycChecklist" => [
							[
								"label" => "<h3>Certificate of Registration</h3><p><i>(Business Name)</i>:Depending on the registration details e.g. Limited Company, Partnership etc. then the related/specified documents above should be attached</p>",
								"value" => "Certificate of Registration (Business Name): Depending on the registration details e.g. Limited Company, Partnership etc. then the related/specified documents above should be attached"
							],
							[
								"label" => "<h3>Copy of the School Registration Certificate</h3><p>(Issued by Ministry of Education & Sports - M.o.E.S):This will be submitted if a school has been in existence for a period that is more than two years.</p>",
								"value" => "Copy of the School Registration Certificate (Issued by Ministry of Education & Sports - M.o.E.S): This will be submitted if a school has been in existence for a period that is more than two years."
							],
							[
								"label" => "<h3>Copy of the License from the Ministry of Education:</h3><p>This will be submitted if a school is in existence for a period that is less than two years ONLY.</p>",
								"value" => "Copy of the License from the Ministry of Education: This will be submitted if a school is in existence for a period that is less than two years ONLY."
							],
							[
								"label" => "<h3>Proof of ownership</h3><p>about a given business type, as detailed in 1 above.</p>",
								"value" => "Proof of ownership, about a given business type, as detailed in 1 above"
							],
							[
								"label" => "<h3>Resolution/Letter requesting /applying for Use of Mobile Money</h3><p>signed by the authorized entities as referenced in the founding documents, holding respective offices that are mandated to open accounts on behalf of the business entity and indicating who the authorized signatories will be for the Mobile Money wallet/ account (s).</p>",
								"value" => "Resolution/Letter requesting /applying for Use of Mobile Money signed by the authorized entities as referenced in the founding documents, holding respective offices that are mandated to open accounts on behalf of the business entity and indicating who the authorized signatories will be for the Mobile Money wallet/ account (s)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties </h3><p>(Shares >=25%, Directors, Account Signatories): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Shares >=25%, Directors, Account Signatories): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption.</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption"
							]
						]
					],
					[
						"label" => "Religious worship, churches, mosques etc",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified Copy of Certificate of Registration of parent</h3><p><i>(Company or its equivalent)</i>:Depending on the registration details, for example a Limited company and Partnership, then the related documents will be attached such as a Registration Certificate from the parent body, for example but not limited to the Church and Mosque</p>",
								"value" => "Certified Copy of Certificate of Registration of parent(Company or its equivalent): Depending on the registration details, for example a Limited company and Partnership, then the related documents will be attached such as a Registration Certificate from the parent body, for example but not limited to the Church and Mosque"
							],
							[
								"label" => "<h3>An Introduction/recommendation letter</h3><p>from the Parent/ Mother entity</p>",
								"value" => "An Introduction/recommendation letter from the Parent/ Mother entity"
							],
							[
								"label" => "<h3>A recommendation letter from respective Secretariat:</h3><p>Indicate the name of the secretariat For Non-traditional religious faith, consider the license from URSB (where applicable).</p>",
								"value" => "A recommendation letter from respective Secretariat: Indicate the name of the secretariat For Non-traditional religious faith, consider the license from URSB (where applicable)"
							],
							[
								"label" => "<h3>Registered/Certified resolution/Letter for use of Mobile Money</h3><p>Services signed by the authorized persons.</p>",
								"value" => "Registered/Certified resolution/Letter for use of Mobile Money Services signed by the authorized persons"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p>(Directors/Nominated Administrators):Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Directors/Nominated Administrators): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Authorization letter to open mobile money from the Vicar/Secretariat</h3><p>of the religious faith for the mainstream churches/mosques and or registration documents of the URSB depending on the mode of registration, and indicating who the Authorized signatories are to the mobile money wallet / account</p>",
								"value" => "Authorization letter to open mobile money from the Vicar/Secretariat of the religious faith for the mainstream churches/mosques and or registration documents of the URSB depending on the mode of registration, and indicating who the Authorized signatories are to the mobile money wallet / account"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Letter from Local Council Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Letter from Local Council Tenancy Agreement Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number</h3><p>(TIN), or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Cultural institutions e.g king/chiefdoms & any related activity projects",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter from the Kingdom Prime Minister requesting for Mobile Money Services</h3><p>expressing the Kingdoms mandate to apply for the services and stating the authorized individuals to handle matters related to the service on behalf of the Kingdom.</p>",
								"value" => "Letter from the Kingdom Prime Minister requesting for Mobile Money Services expressing the Kingdoms mandate to apply for the services and stating the authorized individuals to handle matters related to the service on behalf of the Kingdom"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p><i> (Authorized Signatories/Nominated Administrators)</i>:Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Authorized Signatories/Nominated Administrators): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council, Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Tenancy Agreement Letter from Local Council, Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number</h3><p>(TIN), or <strong>Tax Exemption Certificate.</strong></p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate"
							]
						]
					],
					[
						"label" => "Existing momo partner",
						"kycChecklist" => [[
							"label" => "<h3>MTN Open API integration Request Letter signed by at least two authorized signatories</h3><p></p>",
							"value" => "Identity Documents of Primary and related parties(Directors & Authorized Account Signatories): Acceptable IDs are: National ID (front and back) for Ugandan Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
						]]
					],
					[
						"label" => "Trustees",
						"kycChecklist" => [
							[
								"label" => "<h3>Registered & Certified Board Resolution/ Authorization to Open Mobile money account</h3><p>and indicating the authorized signatories.</p>",
								"value" => "Registered & Certified Board Resolution/ Authorization to Open Mobile money account and indicating the authorized signatories"
							],
							[
								"label" => "<h3>Registered & Certified Certificate for registration</h3>",
								"value" => "Registered & Certified Certificate for registration"
							],
							[
								"label" => "<h3>Registered & Certified copy of the Trust Deed</h3><p>and/ Constitution/ certified copy of the By Laws.</p>",
								"value" => "Registered & Certified copy of the Trust Deed, and/ Constitution/ certified copy of the By Laws"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p><i> (Director/Trustees/Administrators, Authorized Signatories)</i>:Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees.</p>",
								"value" => "Identity Documents of Primary and Related Parties (Director/Trustees/Administrators, Authorized Signatories): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals, Passport & Entry VISA and/or Work Permit for Foreigners and Refugee ID/Refugee Attestations for Refugees"
							],
							[
								"label" => "<h3>Registered & Certified Particulars of Trustees</h3>",
								"value" => "Registered & Certified Particulars of Trustees"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Government / Local Authority Bills Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, OR Advocate, Government / Local Authority Bills Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number</h3><p>(TIN), or <strong>Tax Exemption Certificate</strong> / any other evidence of exemption</p>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate / any other evidence of exemption"
							]
						]
					],
					[
						"label" => "Political parties",
						"kycChecklist" => [
							[
								"label" => "<h3>Certified copy of Registration Certificate from the Electoral Commission.</h3>",
								"value" => "Certified copy of Registration Certificate from the Electoral Commission"
							],
							[
								"label" => "<h3>A certified copy of the party Constitution</h3><p>that is filed with the Electoral Commission.</p>",
								"value" => "A certified copy of the party Constitution that is filed with the Electoral Commission"
							],
							[
								"label" => "<h3>A Party Resolution to open a Mobile Money Account with MTN:</h3><p>The Resolution must have been filed with the Registrar of Documents- URSB, indicating the authorized signatories to the Mobile Money account(s)</p>",
								"value" => "A Party Resolution to open a Mobile Money Account with MTN: The Resolution must have been filed with the Registrar of Documents- URSB, indicating the authorized signatories to the Mobile Money account(s)"
							],
							[
								"label" => "<h3>Identity Documents of Primary and Related Parties</h3><p><i> (the Party President, Secretary General, Treasurer of the party and Account Signatories)</i>:Acceptable IDs are: National ID (front and back) for Cameroonn Nationals</p>",
								"value" => "Identity Documents of Primary and Related Parties (the Party President, Secretary General, Treasurer of the party and Account Signatories): Acceptable IDs are: National ID (front and back) for Cameroonn Nationals"
							],
							[
								"label" => "<h3>Proof of Address of the business name entity:</h3><p>Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Copy of the Tenancy Agreement Bank Statement bearing business entity address.</p>",
								"value" => "Proof of Address of the business name entity: Any of the following below can be obtained; Recent Utility bills not older than three months, Introduction from existing financial institution, Government / Local Authority Bills Copy of the Tenancy Agreement Bank Statement bearing business entity address"
							],
							[
								"label" => "<h3>Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption.</h3>",
								"value" => "Tax Identification Number (TIN), or Tax Exemption Certificate or any other evidence of exemption"
							]
						]
					]
				]
			],
			"bj" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Benin_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Non-governmental organization (ngo)",
						"kycChecklist" => [
							[
								"label" => "<h3>Contrat - Depending on the Product</h3><p><i>Fill and sign contract and then upload them again in the field below</i></p>",
								"value" => "Contrat - Depending on the Product. Fill and sign contract and then upload them again in the field below"
							],
							[
								"label" => "<h3>Creation Decree</h3><p><i>Upload company creation decree</i></p>",
								"value" => "Creation Decree. Upload company creation decree"
							],
							[
								"label" => "<h3>Statutes of the NGO</h3><p><i>Upload Statutes Non-governmental organization</i></p>",
								"value" => "Statutes of the NGO. Upload Statutes Non-governmental organization"
							],
							[
								"label" => "<h3>Copy of main responsible National ID Card</h3><p><i>Upload copy of main responsible National ID Card (CNI)</i></p>",
								"value" => "Copy of main responsible National ID Card. Upload copy of main responsible National ID Card (CNI)"
							],
							[
								"label" => "<h3>test cases document</h3><p><i>Upload signed test cases document </i></p>",
								"value" => "test cases document. Upload signed test cases document"
							]
						]
					],
					[
						"label" => "Private company",
						"kycChecklist" => [
							[
								"label" => "<h3>Contrat - Depending on the Product</h3><p><i>Fill and sign contract and then upload them again in the field below</i></p>",
								"value" => "Contrat - Depending on the Product. Fill and sign contract and then upload them again in the field below"
							],
							[
								"label" => "<h3>Trade Register (RC) </h3><p><i>Upload trade register</i></p>",
								"value" => "Trade Register (RC). Upload trade register"
							],
							[
								"label" => "<h3>Copy of main responsible National ID Card </h3><p><i>Upload main responsible National ID card(CNI)</i></p>",
								"value" => "Copy of main responsible National ID Card. Upload main responsible National ID card(CNI)"
							],
							[
								"label" => "<h3>test cases document</h3><p><i>Upload signed test cases document signed</i></p>",
								"value" => "test cases document. Upload signed test cases document signed"
							],
							[
								"label" => "<h3>Tax declaration (DFE) (Optional)</h3><p><i>Upload Tax declaration</i></p>",
								"value" => "Tax declaration (DFE) (Optional). Upload Tax declaration",
								"required" => false
							]
						]
					],
					[
						"label" => "Public and government",
						"kycChecklist" => [
							[
								"label" => "<h3>Contrat - Depending on the Product</h3><p><i>Fill and sign contract and then upload them again in the field below</i></p>",
								"value" => "Contrat - Depending on the Product. Fill and sign contract and then upload them again in the field below"
							],
							[
								"label" => "<h3>Creation Decree</h3><p><i>Upload company creation decree</i></p>",
								"value" => "Creation Decree. Upload company creation decree"
							],
							[
								"label" => "<h3>Statutes of the NGO </h3><p><i>Upload Statutes Non-governmental organization</i></p>",
								"value" => "Statutes of the NGO. Upload Statutes Non-governmental organization"
							],
							[
								"label" => "<h3>Copy of main responsible National ID Card</h3><p><i>Upload main responsible National ID card(CNI)</i></p>",
								"value" => "Copy of main responsible National ID Card. Upload main responsible National ID card(CNI)"
							],
							[
								"label" => "<h3>test cases document</h3><p><i>Upload test cases document</i></p>",
								"value" => "test cases document. Upload test cases document"
							]
						]
					]
				]
			],
			"cd" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Congo_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Persone physique",
						"kycChecklist" => [
							[
								"label" => "<h3>Pièce nationale d’identité en cours de validité</h3>",
								"value" => "Pièce nationale d’identité en cours de validité"
							],
							[
								"label" => "<h3>Attestation de résidence ou copies Facture SNE, SNDE</h3>",
								"value" => "Attestation de résidence ou copies Facture SNE, SNDE"
							]
						]
					],
					[
						"label" => "Personne morale",
						"kycChecklist" => [
							[
								"label" => "<h3>Registre de commerce</h3>",
								"value" => "Registre de commerce"
							],
							[
								"label" => "<h3>Contrat de bail  ou NIU, Bail ou une facture SNE, SNDE</h3>",
								"value" => "Contrat de bail  ou NIU, Bail ou une facture SNE, SNDE"
							],
							[
								"label" => "<h3>Statuts</h3>",
								"value" => "Statuts"
							],
							[
								"label" => "<h3>Patente en cours de validité</h3>",
								"value" => "Patente en cours de validité"
							],
							[
								"label" => "<h3>Scien</h3>",
								"value" => "Scien"
							],
							[
								"label" => "<h3>Sciet</h3>",
								"value" => "Sciet"
							],
							[
								"label" => "<h3>Copie Carte d’Identité du ou des signataires en cours de validité …</h3>",
								"value" => "Copie Carte d’Identité du ou des signataires en cours de validité …"
							],
							[
								"label" => "<h3>Attestation d’inscription à la chambre de corporation ( pour les profession libérale )</h3>",
								"value" => "Attestation d’inscription à la chambre de corporation ( pour les profession libérale )"
							]
						]
					],
				]
			],
			"sz" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Swaziland_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Company",
						"kycChecklist" => [
							[
								"label" => "<h3>Memorandum and Articles of Association</h3>",
								"value" => "Memorandum and Articles of Association"
							],
							[
								"label" => "<h3>Resolution to open Account</h3>",
								"value" => "Resolution to open Account"
							],
							[
								"label" => "<h3>Certificate of Incorporation</h3>",
								"value" => "Certificate of Incorporation"
							],
							[
								"label" => "<h3>Trading License</h3>",
								"value" => "Trading License"
							],
							[
								"label" => "<h3>Form J & C</h3>",
								"value" => "Form J & C"
							],
							[
								"label" => "<h3>Tax Clearance Certificate</h3>",
								"value" => "Tax Clearance Certificate"
							],
							[
								"label" => "<h3>Lease Agreement for Company Premises (Where Applicable) or proof of physical address for entity</h3>",
								"value" => "Lease Agreement for Company Premises (Where Applicable) or proof of physical address for entity"
							],
							[
								"label" => "<h3>Proof of residence for Directors with more than 25% shareholding</h3>",
								"value" => "Proof of residence for Directors with more than 25% shareholding"
							],
							[
								"label" => "<h3>National Identity document for all Directors</h3>",
								"value" => "National Identity document for all Directors"
							]
						]
					],

					[
						"label" => "Sole trader",
						"kycChecklist" => [
							[
								"label" => "<h3>Trading License</h3>",
								"value" => "Trading License"
							],
							[
								"label" => "<h3>Tax Clearance Certificate</h3>",
								"value" => "Tax Clearance Certificate"
							],
							[
								"label" => "<h3>Proof of physical address for entity</h3>",
								"value" => "Proof of physical address for entity"
							],
							[
								"label" => "<h3>Proof of Residence for Sole Trader</h3>",
								"value" => "Proof of Residence for Sole Trader"
							],
							[
								"label" => "<h3>National Identity document for the director</h3>",
								"value" => "National Identity document for the director"
							]
						]
					],

					[
						"label" => "Partnership",
						"kycChecklist" => [
							[
								"label" => "<h3>Copy of Constitution or Deed of Partnership</h3>",
								"value" => "Copy of Constitution or Deed of Partnership"
							],
							[
								"label" => "<h3>Partnership Resolution for Account opening</h3>",
								"value" => "Partnership Resolution for Account opening"
							],
							[
								"label" => "<h3>Proof of physical address for entity</h3>",
								"value" => "Proof of physical address for entity"
							],
							[
								"label" => "<h3>Proof of residence for Signatories/Partners</h3>",
								"value" => "Proof of residence for Signatories/Partners"
							],
							[
								"label" => "<h3>National Identity document for Signatories/Partners</h3>",
								"value" => "National Identity document for Signatories/Partners"
							]
						]
					],
					[
						"label" => "Cooperatives & association customers",
						"kycChecklist" => [
							[
								"label" => "<h3>Constitution / By laws</h3>",
								"value" => "Constitution / By laws"
							],
							[
								"label" => "<h3>Commissioner of Co-ops certificate</h3>",
								"value" => "Commissioner of Co-ops certificate"
							],
							[
								"label" => "<h3>Proof of registration with FSRA</h3>",
								"value" => "Proof of registration with FSRA"
							],
							[
								"label" => "<h3>Minutes authorizing opening of an account account/ Resolution to open Account</h3>",
								"value" => "Minutes authorizing opening of an account account/ Resolution to open Account"
							],
							[
								"label" => "<h3>Proof of address for Co-op</h3>",
								"value" => "Proof of address for Co-op"
							],
							[
								"label" => "<h3>Proof of Residence of Signatories</h3>",
								"value" => "Proof of Residence of Signatories"
							],
							[
								"label" => "<h3>National Identity documents for Signatories</h3>",
								"value" => "National Identity documents for Signatories"
							]
						]
					],

					[
						"label" => "Churches",
						"kycChecklist" => [
							[
								"label" => "<h3>Church Constitution</h3>",
								"value" => "Church Constitution"
							],
							[
								"label" => "<h3>Resolution to open Mobile Money Account</h3>",
								"value" => "Resolution to open Mobile Money Account"
							],
							[
								"label" => "<h3>Signatories’ proof of residence documents</h3>",
								"value" => "Signatories’ proof of residence documents"
							],
							[
								"label" => "<h3>Proof of physical address (confirmed by letter from city council / Umphakatsi depending on the location of the church)</h3>",
								"value" => "Proof of physical address (confirmed by letter from city council / Umphakatsi depending on the location of the church)"
							],
							[
								"label" => "<h3>Proof of Residence of Signatories</h3>",
								"value" => "Proof of Residence of Signatories"
							],
							[
								"label" => "<h3>National Identity for Signatories</h3>",
								"value" => "National Identity for Signatories"
							]
						]
					],

					[
						"label" => "Sports teams",
						"kycChecklist" => [
							[
								"label" => "<h3>Constitution</h3>",
								"value" => "Constitution"
							],
							[
								"label" => "<h3>Affiliation letter from recognized body e.g. Football Association; Swaziland National Basketball Association</h3>",
								"value" => "Affiliation letter from recognized body e.g. Football Association; Swaziland National Basketball Association"
							],
							[
								"label" => "<h3>Minutes of meeting when decision was taken to open and account/ Resolution to open Account</h3>",
								"value" => "Minutes of meeting when decision was taken to open and account/ Resolution to open Account"
							],
							[
								"label" => "<h3>Proof of physical address</h3>",
								"value" => "Proof of physical address"
							],
							[
								"label" => "<h3>Proof of Residence of Signatories</h3>",
								"value" => "Proof of Residence of Signatories"
							],
							[
								"label" => "<h3>National Identity for Signatories</h3>",
								"value" => "National Identity for Signatories"
							]
						]
					],

					[
						"label" => "Government agencies / ministries",
						"kycChecklist" => [
							[
								"label" => "<h3>Open API Application Form B (Corporate)</h3>",
								"value" => "Open API Application Form B (Corporate)"
							],
							[
								"label" => "<h3>Letter signed by Minister in Charge</h3>",
								"value" => "Letter signed by Minister in Charge"
							],
							[
								"label" => "<h3>Identification documents of at least 2 Directors</h3>",
								"value" => "Identification documents of at least 2 Directors"
							],
							[
								"label" => "<h3>Proof of address of 2 Directors</h3>",
								"value" => "Proof of address of 2 Directors"
							]
						]
					],

					[
						"label" => "Schools",
						"kycChecklist" => [
							[
								"label" => "<h3>Letter from Regional Office (REO)</h3>",
								"value" => "Letter from Regional Office (REO)"
							],
							[
								"label" => "<h3>School committee constitution</h3>",
								"value" => "School committee constitution"
							],
							[
								"label" => "<h3>Minutes of meeting when decision was taken to open and account/ Resolution to open Account</h3>",
								"value" => "Minutes of meeting when decision was taken to open and account/ Resolution to open Account"
							],
							[
								"label" => "<h3>Proof of physical address for School</h3>",
								"value" => "Proof of physical address for School"
							],
							[
								"label" => "<h3>National Identity for Signatories</h3>",
								"value" => "National Identity for Signatories"
							],
							[
								"label" => "<h3>Proof of Residence of Signatories</h3>",
								"value" => "Proof of Residence of Signatories"
							]
						]
					],

					[
						"label" => "Non-profit making organizations(ngos)",
						"kycChecklist" => [
							[
								"label" => "<h3>Constitution</h3>",
								"value" => "Constitution"
							],
							[
								"label" => "<h3>Minutes of meeting when decision was taken to open and account/ Resolution to open Account</h3>",
								"value" => "Minutes of meeting when decision was taken to open and account/ Resolution to open Account"
							],
							[
								"label" => "<h3>Proof of physical address for NGO</h3>",
								"value" => "Proof of physical address for NGO"
							],
							[
								"label" => "<h3>Proof of Residence of Signatories</h3>",
								"value" => "Proof of Residence of Signatories"
							],
							[
								"label" => "<h3>National Identity for Signatories</h3>",
								"value" => "National Identity for Signatories"
							],
							[
								"label" => "<h3>Other applicable documents</h3>",
								"value" => "Other applicable documents"
							]
						]
					]
				]
			],
			"gn" => [
				"apiTermsAndConditions" => "https://momodeveloper.mtn.com/Guinea-C_apitermsandcondition",
				"pdf" => "/kyc/momo/kyc-{$country}.pdf",
				"businessTypes" => [
					[
						"label" => "Societe (sarlu, sarlu, sa, …)",
						"kycChecklist" => [
							[
								"label" => "<h3>RCCM (Registre de Commerce et du Crédit Mobilier)</h3>",
								"value" => "RCCM (Registre de Commerce et du Crédit Mobilier)"
							],
							[
								"label" => "<h3>LES STATUTS DE LA SOCIETE</h3>",
								"value" => "LES STATUTS DE LA SOCIETE"
							],
							[
								"label" => "<h3>PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)</h3>",
								"value" => "PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)"
							],
							[
								"label" => "<h3>CERTIFICAT D’IMMATRICULATION FISCALE (NIF)</h3>",
								"value" => "CERTIFICAT D’IMMATRICULATION FISCALE (NIF)"
							]
						]
					],

					[
						"label" => "Etablissements (ets)",
						"kycChecklist" => [
							[
								"label" => "<h3>RCCM (Registre de Commerce et du Crédit Mobilier)</h3>",
								"value" => "RCCM (Registre de Commerce et du Crédit Mobilier)"
							],
							[
								"label" => "<h3>PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)</h3>",
								"value" => "PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)"
							],
							[
								"label" => "<h3>CERTIFICAT D’IMMATRICULATION FISCALE (NIF)</h3>",
								"value" => "CERTIFICAT D’IMMATRICULATION FISCALE (NIF)"
							]
						]
					],

					[
						"label" => "Ong et association",
						"kycChecklist" => [
							[
								"label" => "<h3>AUTORISATION ADMINISTRATIVE ou L’AGREMENT</h3>",
								"value" => "AUTORISATION ADMINISTRATIVE ou L’AGREMENT"
							],
							[
								"label" => "<h3>PV DE NOMINATION DU OU DES REPRESENTANTS LEGAUX</h3>",
								"value" => "PV DE NOMINATION DU OU DES REPRESENTANTS LEGAUX"
							],
							[
								"label" => "<h3>PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)</h3>",
								"value" => "PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)"
							]
						]
					],
					[
						"label" => "Organisme etatique",
						"kycChecklist" => [
							[
								"label" => "<h3>DECISION ADMINISTARTIVE POUR LES INSTITUTIONS PUBLIQUE ;</h3>",
								"value" => "DECISION ADMINISTARTIVE POUR LES INSTITUTIONS PUBLIQUE ;"
							],
							[
								"label" => "<h3>ACTE DE NOMINATION DU OU DES REPRESENTANTS LEGAUX (si la décision administrative ne le mentionne pas)</h3>",
								"value" => "ACTE DE NOMINATION DU OU DES REPRESENTANTS LEGAUX (si la décision administrative ne le mentionne pas)"
							],
							[
								"label" => "<h3>PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)</h3>",
								"value" => "PIECE (S) D’IDENTITE (S) DU OU DES REPRESENTANTS LEGAUX (Carte Nationale d’Identité, Carte Electeur, ou le Passeport)"
							]
						]
					]
				]
			]
		][$country] ?? [];
	}
}
