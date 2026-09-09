<?php

namespace AdinanCenci\JsonLines\Search\Iterator;

use AdinanCenci\FileEditor\Search\Iterator\DataWrapper as LineWrapper;
use AdinanCenci\FileEditor\Search\Iterator\DataWrapperInterface;

/**
 * {@inheritdoc}
 */
class DataWrapper extends LineWrapper implements DataWrapperInterface
{
    /**
     * Constructor.
     *
     * @param array|\stdClass $data
     *   The parsed JSON.
     */
    public function __construct(protected mixed $data)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        if (is_array($this->data) || $this->data instanceof \stdClass) {
            return json_encode($this->data);
        }

        return (string) $this->data;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRootOfTheValue(): mixed
    {
        return $this->data;
    }
}
