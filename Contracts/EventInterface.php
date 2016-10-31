<?php

namespace Push\Contracts;

/**
 * Any event which is passed over a channel
 *
 * Interface EventInterface
 * @package Push
 */
interface EventInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data);

    /**
     * @return array
     */
    public function getData();

}