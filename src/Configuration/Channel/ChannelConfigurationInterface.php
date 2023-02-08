<?php

/*
 *  This file is part of the Micro framework package.
 *
 *  (c) Stanislau Komar <kost@micro-php.net>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Micro\Plugin\Amqp\Configuration\Channel;

use Micro\Plugin\Amqp\Configuration\Binding\BindingConfigurationInterface;

interface ChannelConfigurationInterface
{
    /**
     * @return BindingConfigurationInterface[]
     */
    public function getBindings(): array;
}
