<?php

namespace AdinanCenci\JsonLines\Search\Iterator;

use AdinanCenci\FileEditor\Search\Iterator\MetadataWrapper as LineWrapper;
use AdinanCenci\FileEditor\Search\Iterator\MetadataWrapperInterface;

/**
 * {@inheritdoc}
 */
class MetadataWrapper extends LineWrapper implements MetadataWrapperInterface
{
    /**
     * Constructor.
     *
     * @param int $position
     *   The position of the line within the file.
     * @param array|\stdClass $data
     *   The parsed JSON.
     */
    public function __construct(int $position, mixed $data)
    {
        $this->position = $position;
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function fallbackCompute(string $propertyName)
    {
        if (isset($this->data->{$propertyName})) {
            return $this->data->{$propertyName};
        }

        return parent::fallbackCompute($propertyName);
    }

    /**
     * {@inheritdoc}
     */
    protected function fallbackIsset(string $propertyName)
    {
        if (isset($this->data->{$propertyName})) {
            return true;
        }

        return parent::fallbackIsset($propertyName);
    }
}
