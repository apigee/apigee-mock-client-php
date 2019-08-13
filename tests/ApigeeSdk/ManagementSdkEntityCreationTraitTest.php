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

namespace Apigee\MockClient\Tests\ApigeeSdk;

use Apigee\Edge\Api\Management\Controller\OrganizationController;
use Apigee\Edge\Structure\PropertiesProperty;
use Apigee\MockClient\ApigeeSdk\ManagementSdkEntityCreationTrait;
use Apigee\MockClient\ApigeeSdk\SdkMockClient;
use Apigee\MockClient\Generator\ApigeeSdkEntityGenerator;
use Apigee\MockClient\MockClient;
use Apigee\MockClient\ResponseFactory;
use Apigee\MockClient\Tests\RandomStringGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * Tests Entity response generation.
 */
class ManagementSdkEntityCreationTraitTest extends TestCase {

  use ManagementSdkEntityCreationTrait;
  use RandomStringGeneratorTrait;

  public function setUp() {
    parent::setUp();

    $this->mockClient = new MockClient();
    $this->sdkClient = new SdkMockClient($this->mockClient);
    $this->responseFactory = new ResponseFactory();
    $this->responseFactory->addGenerator(new ApigeeSdkEntityGenerator());
  }

  /**
   * Tests the organization creator trait.
   */
  public function testCreateSdkOrganization() {
    $organization = $this->createOrganizationSdkEntity([
      'name' => $this->randomCharacters(),
      'properties' => [new PropertiesProperty(["name" => "subscriptionType","value" => "enterprise"])],
    ]);

    $organization_controller = new OrganizationController($this->sdkClient);

    static::assertSame('GET', $this->mockClient->getLastRequest()->getMethod());

    $organization_controller->update($organization);
    $organization_controller->delete($organization->id());
  }

}
