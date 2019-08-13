<?php

/*
 * Copyright 2019 The Apigee Mock Client PHP Authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Apigee\MockClient\ApigeeSdk;

use Apigee\Edge\Client;
use Apigee\Edge\HttpClient\Utility\Builder;
use Apigee\MockClient\MockClient;
use Http\Message\Authentication\Chain;

/**
 * A client for testing the Apigee SDK.
 */
class SdkMockClient extends Client {

  /**
   * The http client.
   *
   * @var \Apigee\Edge\Tests\Test\HttpClient\MockHttpClientInterface
   */
  private $httpClient;

  /**
   * SdkMockClient constructor.
   *
   * @param \Apigee\MockClient\MockClient $mock_client
   *  The mock client.
   *
   * {@inheritdoc}
   */
  public function __construct(MockClient $mock_client, $authentication = NULL, string $endpoint = null, array $options = []) {
    $this->httpClient = $mock_client;
    $options += [
      Client::CONFIG_HTTP_CLIENT_BUILDER => new Builder($this->httpClient),
      Client::CONFIG_USER_AGENT_PREFIX => 'OFFLINE',
    ];

    // The chain plugin does nothing without authentication plugins added.
    $authentication = $authentication ?? new Chain();

    parent::__construct($authentication, $endpoint, $options);
  }

  /**
   * @inheritdoc
   */
  public function getMockHttpClient() {
    return $this->httpClient;
  }

}
