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
 * Interface for classes that can generate a mock response from source data.
 */
interface ResponseGeneratorInterface {

  /**
   * Determine if a generator can generate a response for a data source.
   *
   * @param mixed $source
   *   The source data that would be used to generate the mock response.
   *
   * @return bool
   *   Whether or not the generator can transform the source object into a
   *   response.
   */
  public function appliesToSource($source);

  /**
   * Generate a mock response from a data source.
   *
   * @param $source
   *   The data source object.
   *
   * @return \Psr\Http\Message\ResponseInterface|\Exception
   *   The mock response or an exception to be thrown.
   */
  public function generateFromSource($source);

}
