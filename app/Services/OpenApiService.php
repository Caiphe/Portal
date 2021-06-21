<?php

namespace App\Services;

use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

/**
 * OpenApi class
 */
class OpenApiService
{
	private $openApi;

	function __construct($openApi)
	{
		$this->openApi = self::yamlToJson($openApi);
		$this->auth = $this->buildAuth();

		$this->openApi['basePath'] ??= '';
	}

	public static function yamlToJson($openApi)
	{
		return Yaml::parse(file_get_contents(public_path() . "/openapi/" . $openApi));
	}

	public function buildOpenApiJson()
	{
		$openApiJson = [
			"info" => [
				"name" => $this->openApi['info']['title'],
				"schema" =>
				"https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
			],
			"item" => $this->buildItems(),
		];

		return $openApiJson;
	}

	protected function buildItems()
	{
		$items = [];
		$i = 0;
		$params = [];
		foreach ($this->openApi['paths'] as $url => $methods) {
			$params = [];
			foreach ($methods as $method => $request) {
				if ($method === 'parameters') {
					$params = $request;
					continue;
				} elseif (
					!isset($request['parameters']) &&
					!empty($params)
				) {
					$request['parameters'] = $params;
				}
				$urlParts = array_filter(explode('/', $url));
				if (
					isset($this->openApi['basePath']) &&
					!empty($this->openApi['basePath'])
				) {
					$urlParts = array_merge(
						array_filter(explode('/', $this->openApi['basePath'])),
						$urlParts
					);
				}

				$requestName =
					$request['summary'] ??
					$request['name'] ??
					Str::slug($url);

				$headers = $this->buildFormHeader($request['parameters']);
				$parameters = $this->buildFormData($request['parameters']);

				$items[] = [
					"name" => $requestName,
					"description" => $request['description'] ?? '',
					"request" => [
						"auth" => $this->auth,
						"method" => ucwords($method),
						"header" => $headers,
						"url" => [
							"raw" => $this->openApi['host'] . preg_replace('/\/$/', '', $this->openApi['basePath']) . $url,
							"protocol" => "https",
							"host" => explode('.', $this->openApi['host']),
							"path" => $urlParts,
						],
						"code" => $this->getCode($url, $method, $headers, $parameters)
					],
					"response" => $this->buildResponses($request['responses']),
				];

				if (isset($parameters['query'])) {
					$items[$i]['request']['url']['query'] =
						$parameters['query'];
				}
				if (isset($parameters['body'])) {
					$items[$i]['request']['body'] = $parameters['body'];
				}

				$i++;
			}
		}

		return $items;
	}

	protected function buildFormData($parameters)
	{
		$queries = array_filter($parameters, function ($parameter) {
			return $parameter['in'] === 'query' || $parameter['in'] === 'body';
		});

		return array_reduce(
			$queries,
			function ($carry, $query) {
				if (isset($query['schema'])) {
					if ($query['in'] === 'body') {
						$schemaFormData = [
							'body' => [
								"mode" => "formdata",
								"formdata" => $this->buildFormDataFromRef(
									$query['schema']['$ref']
								),
							],
						];
					} else {
						$schemaFormData = [
							$query['in'] => $this->buildFormDataFromRef(
								$query['schema']['$ref']
							),
						];
					}
					return array_merge($carry, $schemaFormData);
				}

				$carry[$query['in']][] = [
					"key" => $query['name'],
					"value" => '',
					"required" => $query['required'] ?? 0,
					"type" => $query['type'],
					"description" => $query['description'] ?? '',
				];

				return $carry;
			},
			[]
		);
	}

	protected function buildFormDataFromRef($ref, $aditionalInfo = '')
	{
		$properties = [];
		if (gettype($ref) === 'string') {
			$definition = explode('/', $ref);
			$definition = $this->openApi[$definition[1]][$definition[2]];

			if (isset($definition['x-enum-elements'])) {
				return [
					"key" => $aditionalInfo,
					"value" =>
					isset($definition['default']) &&
						$definition['default'] !== 'null'
						? $definition['default']
						: '',
					"description" =>
					$definition['description'] ??
						implode(', ', $definition['enum']) ??
						'',
					"required" => $definition['required'] ?? 0,
					"type" => $definition['type'],
				];
			} elseif (isset($definition['enum'])) {
				return [
					"key" => $aditionalInfo,
					"value" => $definition['default'] ?? '',
					"description" =>
					$definition['description'] ??
						implode(', ', $definition['enum']) ??
						'',
					"required" => $definition['required'] ?? 0,
					"type" => $definition['type'],
				];
			}

			$properties = [];
			if (!isset($definition['properties'])) {
				$definitionKey = array_keys($definition)[0];
				$properties = $definition[$definitionKey]['properties'];
			} else {
				$properties = $definition['properties'];
			}
		} else {
			$properties = $ref;
		}

		$required = $definition['required'] ?? [];
		$propertyKeys = array_keys($properties);
		return array_map(function ($property) use ($properties, $required) {
			if (isset($properties[$property]['additionalProperties'])) {
				return $this->buildFormDataFromRef(
					$properties[$property]['additionalProperties']
				);
			}

			if (isset($properties[$property]['$ref'])) {
				return $this->buildFormDataFromRef(
					$properties[$property]['$ref'],
					$property
				);
			}

			if (
				isset($properties[$property]['type']) &&
				$properties[$property]['type'] === 'object'
			) {
				return $this->buildFormDataFromRef(
					$properties[$property]['properties']
				);
			}

			return [
				"key" => $property,
				"value" =>
				isset($properties[$property]['default']) &&
					$properties[$property]['default'] !== 'null'
					? $properties[$property]['default']
					: '',
				"description" => $properties[$property]['description'] ?? '',
				"required" => +in_array($property, $required),
				"type" => $properties[$property]['type'] ?? '',
			];
		}, $propertyKeys);
	}

	protected function buildFormHeader($parameters)
	{
		$queries = array_filter($parameters, function ($parameter) {
			return $parameter['in'] === 'header';
		});

		$queries[] = [
			"key" => "Content-Type",
			"name" => "Content-Type",
			"type" => "application/json",
			"default" => $this->openApi['consumes'][0] ?? "application/json",
		];

		return array_map(function ($query) {
			return [
				"key" => $query['name'] ?? '',
				"name" => $query['name'] ?? '',
				"value" => $query['default'] ?? '',
				"type" => $query['type'] ?? '',
				"description" => $query['description'] ?? '',
			];
		}, $queries);
	}

	protected function buildAuth()
	{
		if (!isset($this->openApi['securityDefinitions'])) {
			return [];
		}

		$securityDefinitions = $this->openApi['securityDefinitions'];
		$firstDefinitionKey = array_keys($securityDefinitions)[0] ?? '';

		if (empty($firstDefinitionKey)) {
			return [];
		}

		$authMethod = $securityDefinitions[$firstDefinitionKey];
		$authMethodCall = 'get' . ucfirst(strtolower($authMethod['type']));

		return $this->$authMethodCall($authMethod);
	}

	protected function getApikey($details)
	{
		return [
			"type" => "apikey",
			"apikey" => [
				[
					"key" => "key",
					"value" => $details['name'],
					"type" => "string",
				],
			],
		];
	}

	protected function getOauth2()
	{
		return [
			"type" => "oauth2",
			"oauth2" => [
				[
					"key" => "addTokenTo",
					"value" => "Authorization",
					"type" => "string",
				],
			],
		];
	}

	protected function buildResponses($responses)
	{
		$responseKeys = array_keys($responses);

		$r = array_reduce(
			$responseKeys,
			function ($carry, $key) use ($responses) {
				$schema = isset($responses[$key]['schema'])
					? $this->buildResponsesBody($responses[$key]['schema'])
					: [];
				$body = $this->buildResponsesExample($schema);
				$item = [
					"code" => $key,
					"status" => $this->getStatusCode(
						$key,
						$responses[$key]['description']
					),
					"name" => $responses[$key]['description'] ?? '',
					"schema" => $schema,
					"body" => $body,
					"header" => [
						[
							"key" => "Content-Type",
							"value" => "application/json",
							"description" => "Sending as JSON",
							"type" => "string",
						],
					],
				];
				if (isset($responses[$key]['headers'])) {
					$item['header'] = array_merge(
						$responses[$key]['headers'],
						$item['header']
					);
				}

				$carry[] = $item;

				return $carry;
			},
			[]
		);

		return $r;
	}

	protected function buildResponsesBody($schema)
	{
		if (isset($schema['$ref'])) {
			$definition = explode('/', $schema['$ref']);
			$definition = $this->openApi[$definition[1]][$definition[2]];
		} else if (isset($schema['items']) && isset($schema['items']['$ref'])) {
			$definition = explode('/', $schema['items']['$ref']);
			$definition = $this->openApi[$definition[1]][$definition[2]];
		} else {
			$definition = $schema;
		}

		if (isset($definition['enum'])) {
			return [
				"type" => $definition["type"],
				"default" =>
				isset($definition['default']) &&
					$definition['default'] !== 'null'
					? $definition['default']
					: '',
				"description" =>
				$definition["definition"] ??
					implode(',', $definition['enum']),
			];
		}

		$props = [];
		if (!isset($definition['properties'])) {
			$definitionKey = array_keys($definition)[0];
			$props = $definition[$definitionKey]['properties'];
		} else {
			$props = $definition['properties'];
		}

		$propKeys = array_keys($props);

		$body = array_reduce(
			$propKeys,
			function ($carry, $key) use ($props) {
				$p = [];
				if (isset($props[$key]['additionalProperties'])) {
					$p = $props[$key]['additionalProperties'];
				} elseif (
					isset($props[$key]['properties']) ||
					isset($props[$key]['$ref'])
				) {
					$p = $props[$key];
				} elseif (isset($props[$key]['items'])) {
					$p = $props[$key]['items'];
				}

				if (isset($props[$key]['example']) && gettype($props[$key]['example']) === 'array') {
					$props[$key] = ['type' => 'object', 'example' => $props[$key]['example']];
				}

				if (empty($p)) {
					$carry[$key] = $props[$key];
				} else {
					$carry[$key] = $this->buildResponsesBody($p, $key);
				}

				return $carry;
			},
			[]
		);

		return $body;
	}

	private function buildResponsesExample($schema, $level = 0)
	{
		$exampleKeys = array_keys($schema);
		$types = ["string" => "string", "integer" => 1, "boolean" => true, "array" => [], "object" => []];
		$example = array_reduce(
			$exampleKeys,
			function ($carry, $key) use ($schema, $types) {
				if (isset($schema[$key]['example']) && $schema[$key]['type'] === 'object') {
					$carry[$key] = $schema[$key]['example'];
					return $carry;
				}
				if (!isset($schema[$key]['type']) && isset($schema[$key]['properties'])) {
					$schema[$key]['type'] = 'array';
				} else if (
					!isset($schema[$key]['type']) &&
					isset($schema[$key]['example'])
				) {
					$schema[$key]['type'] = gettype($schema[$key]['example']);
				} else {
					$schema[$key]['type'] = "array";
				}

				if (gettype($schema[$key]['type']) === 'array') {
					$carry[$key] = $this->buildResponsesExample(
						$schema[$key],
						1
					);

					return $carry;
				}

				$carry[$key] =
					isset($schema[$key]['default']) &&
					!empty($schema[$key]['default']) &&
					$schema[$key]['default'] !== 'null'
					? $schema[$key]['default']
					: $types[$schema[$key]['type']] ?? '';

				return $carry;
			},
			[]
		);

		if ($level === 0) {
			return json_encode($example);
		}

		return $example;
	}

	/**
	 * Gets the code examples.
	 *
	 * @param      string  $url         The url
	 * @param      string  $method      The method
	 * @param      array   $headers     The headers
	 * @param      array   $parameters  The parameters
	 *
	 * @return     array  The code examples.
	 */
	protected function getCode(string $url, string $method, array $headers, array $parameters): array
	{
		$code = [];
		$files = \File::allFiles(resource_path('views/stubs/code'));
		$type = [];
		$basePath = preg_replace('/\/$/', '', $this->openApi['basePath']);
		$query = '';

		if (isset($parameters['query'])) {
			$queryArr = [];

			foreach ($parameters['query'] as $q) {
				if ($q['type'] === 'integer') {
					$queryArr[] = "{$q['key']}=" . ($q['value'] ?: 1);
				} else {
					$queryArr[] = "{$q['key']}=" . ($q['value'] ?: 'string');
				}
			}

			$query = '?' . implode('&', $queryArr);
		}

		$url = "https://{$this->openApi['host']}{$basePath}{$url}{$query}";

		if (isset($this->openApi['securityDefinitions']['OAuth2'])) {
			$tokenUrl = $this->openApi['securityDefinitions']['OAuth2']['tokenUrl'] ?? '';
			array_unshift($headers, [
				'key' => 'Authorization',
				'name' => 'Authorization',
				'type' => 'string',
				'description' => '',
				'value' => 'token // ' . $tokenUrl
			]);
		}

		foreach ($files as $file) {
			preg_match('/\/([^\.\/]*).blade.php$/', (string)$file, $type);
			$code[$type[1]] = view("stubs.code.{$type[1]}", [
				'url' => $url,
				'method' => $method,
				'headers' => $headers,
				'parameters' => $parameters,
			])->render();
		}

		return $code;
	}

	private function getStatusCode($status, $description)
	{
		return [
			100 => "Continue",
			101 => "Switching Protocols",
			102 => "Processing",
			200 => "OK",
			201 => "Created",
			202 => "Accepted",
			203 => "Non-authoritative Information",
			204 => "No Content",
			205 => "Reset Content",
			206 => "Partial Content",
			207 => "Multi-Status",
			208 => "Already Reported",
			226 => "IM Used",
			300 => "Multiple Choices",
			301 => "Moved Permanently",
			302 => "Found",
			303 => "See Other",
			304 => "Not Modified",
			305 => "Use Proxy",
			307 => "Temporary Redirect",
			308 => "Permanent Redirect",
			400 => "Bad Request",
			401 => "Unauthorized",
			402 => "Payment Required",
			403 => "Forbidden",
			404 => "Not Found",
			405 => "Method Not Allowed",
			406 => "Not Acceptable",
			407 => "Proxy Authentication Required",
			408 => "Request Timeout",
			409 => "Conflict",
			410 => "Gone",
			411 => "Length Required",
			412 => "Precondition Failed",
			413 => "Payload Too Large",
			414 => "Request-URI Too Long",
			415 => "Unsupported Media Type",
			416 => "Requested Range Not Satisfiable",
			417 => "Expectation Failed",
			418 => "I'm a teapot",
			421 => "Misdirected Request",
			422 => "Unprocessable Entity",
			423 => "Locked",
			424 => "Failed Dependency",
			426 => "Upgrade Required",
			428 => "Precondition Required",
			429 => "Too Many Requests",
			431 => "Request Header Fields Too Large",
			444 => "Connection Closed Without Response",
			451 => "Unavailable For Legal Reasons",
			499 => "Client Closed Request",
			500 => "Internal Server Error",
			501 => "Not Implemented",
			502 => "Bad Gateway",
			503 => "Service Unavailable",
			504 => "Gateway Timeout",
			505 => "HTTP Version Not Supported",
			506 => "Variant Also Negotiates",
			507 => "Insufficient Storage",
			508 => "Loop Detected",
			510 => "Not Extended",
			511 => "Network Authentication Required",
			599 => "Network Connect Timeout Error",
		][$status] ?? $description;
	}
}
