<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Nodes;

/**
 * @deprecated Tie-ing nodes to templates should be done differently; as this creates coupling between the parsing and
 *   rendering phase.
 */
final class TemplatedNode extends Node
{
    /** @var array<string, mixed> */
    private $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(string $value, array $data)
    {
        parent::__construct($value);

        $this->data = $data;
    }

    /** @return array<string, mixed> */
    public function getData(): array
    {
        return $this->data;
    }
}
