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

namespace Apigee\MockClient;

/**
 * The response factory is responsible for finding a matching response generator
 * and getting a response from the generator.
 */
interface ResponseFactoryInterface {

  /**
   * Generate a response.
   *
   * Use this to generate a response. You must first add a generator that
   * recognizes the source object.
   *
   * @param mixed $source
   *   The response source object.
   *
   * @return \Psr\Http\Message\ResponseInterface|\Exception
   *   The http response or an exception to be thrown.
   */
  public function generateResponse($source);

  /**
   * Add a response generator to the response factory.
   *
   * @param \Apigee\MockClient\ResponseGeneratorInterface $generator
   *   The response generator.
   */
  public function addGenerator(ResponseGeneratorInterface $generator);

}
