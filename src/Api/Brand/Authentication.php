<?php

/*
 * This file is part of HasOffers PHP Client.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\HasOffers\Api\Brand;

use DraperStudio\HasOffers\Base;

/**
 * Class ServiceProvider.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class Authentication extends Base
{
    /**
     * API Endpoint Type.
     *
     * @var string
     */
    protected $endpointType = 'Brand';

    /**
     * API Endpoint Name.
     *
     * @var string
     */
    protected $endpointName = 'Authentication';
    /**
     * Find User by email, password and type.
     *
     * @param array $parameters
     *
     * @return object
     */
    public function findUserByCredentials($parameters = [])
    {
        return $this->get('findUserByCredentials', $parameters);
    }

    /**
     * Find User type by session token.
     *
     * @param array $parameters
     *
     * @return object
     */
    public function findUserByToken($parameters = [])
    {
        return $this->get('findUserByToken', $parameters);
    }
}
