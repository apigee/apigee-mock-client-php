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

use Apigee\Edge\Api\Management\Controller\OrganizationController;
use Apigee\Edge\Api\Management\Entity\Organization;
use Apigee\MockClient\Generator\ApigeeSdkEntitySource;
use Http\Message\RequestMatcher\RequestMatcher;

/**
 * A helper trait for generating responses for Apigee SDK entities.
 */
trait ManagementSdkEntityCreationTrait {

  /**
   * The Apigee SDK client.
   *
   * @var \Apigee\Edge\ClientInterface
   */
  protected $sdkClient;

  /**
   * The mock client.
   *
   * @var \Apigee\MockClient\MockClient
   */
  protected $mockClient;

  /**
   * The response factory with the `ApigeeSdkEntityGenerator` added.
   *
   * @var \Apigee\MockClient\ResponseFactoryInterface
   */
  protected $responseFactory;

  public function createOrganizationSdkEntity(array $values = []) {
    // Create the organization.
    $organization = new Organization($values);
    // This covers getting, updating and deleting an organization but not
    // create. A queued response can cover that.
    $this->mockClient->on(
      new RequestMatcher("/v1/organizations/{$organization->id()}", NULL, ['GET', 'PUT', 'DELETE']),
      $this->responseFactory->generateResponse(new ApigeeSdkEntitySource($organization))
    );

    // Return the loaded entity.
    $controller = new OrganizationController($this->sdkClient);
    return $controller->load($organization->id());
  }

}
