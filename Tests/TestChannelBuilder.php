<?php

namespace Push\Tests;


use Push\Models\ChannelBuilder;

/**
 * Class TestChannelBuilder
 * @package Push
 */
class TestChannelBuilder extends PhpUnit {


    public function testBuildFailThrowsProperException() {
        $channelName = 'fdasfj512512jkl4';

        $this->setExpectedException('DeliveryCoreLib\Exceptions\NotFoundException');

        $channel = ChannelBuilder::buildChannelFromName($channelName);

    }
}