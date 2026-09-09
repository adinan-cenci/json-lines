<?php

namespace AdinanCenci\JsonLines\Search\Iterator;

use AdinanCenci\JsonLines\JsonLines;
use AdinanCenci\JsonLines\JsonLinesIterator;
use AdinanCenci\FileEditor\Search\Iterator\Metadata;
use AdinanCenci\FileEditor\Search\Iterator\DataWrapperInterface;

/**
 * Iterator object to scrutinize the document line by line.
 */
class DataIterator extends JsonLinesIterator implements \Iterator
{
    /**
     * Constructor.
     *
     * @param string $filename
     *   The absolute path to the file.
     * @param bool $associative
     *   When true JSON objects will be returned as associative arrays
     *   when false, JSON objects will be returned as objects.
     * @param array $metadataEagerGetters
     *   Array of callbacks to extract metadata for search results.
     * @param array $metadataLazyGetters
     *   Array of callbacks to extract metadata for search results.
     */
    public function __construct(
        protected string $filename,
        protected bool $associative = false,
        protected array $metadataEagerGetters = [],
        protected array $metadataLazyGetters = [],
    ) {
        parent::__construct($filename);
    }

    /**
     * \Iterator::current()
     */
    public function current(): mixed
    {
        if (! $this->getHandle()) {
            return null;
        }

        $object = JsonLines::jsonDecode($this->currentContent, $this->associative, $this->currentLineNumber);
        if (!$object) {
            return false;
        }

        $dataWrapper = new DataWrapper($object);

        $eagerMetadata = $this->compileEagerMetadata($dataWrapper);
        $metadata = new Metadata($dataWrapper, $eagerMetadata, $this->metadataLazyGetters);

        $dataWrapper->setMetadata($metadata);

        return $dataWrapper;
    }

    /**
     * Compiles eager metadata.
     *
     * @param AdinanCenci\FileEditor\Search\Iterator\DataWrapperInterface $dataWrapper
     *   Data wrapper.
     *
     * @return array
     *   Compiled metadata.
     */
    protected function compileEagerMetadata(DataWrapperInterface $dataWrapper): array
    {
        $metadata = [];
        foreach ($this->metadataEagerGetters as $property => $callable) {
            $metadata[$property] = call_user_func($callable, $this, $dataWrapper);
        }
        return $metadata;
    }
}
