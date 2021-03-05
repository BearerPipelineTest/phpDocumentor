<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Guides\Nodes;

use phpDocumentor\Guides\Environment;
use phpDocumentor\Guides\Renderers\NodeRenderer;
use phpDocumentor\Guides\Renderers\RenderedNode;
use RuntimeException;
use function implode;
use function strlen;
use function substr;
use function trim;

abstract class Node
{
    /** @var NodeRenderer|null */
    private $nodeRenderer;

    /** @var Environment|null */
    protected $environment;

    /** @var Node|string|null */
    protected $value;

    /** @var string[] */
    protected $classes = [];

    /**
     * @param Node|string|null $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function setNodeRenderer(NodeRenderer $nodeRenderer) : void
    {
        $this->nodeRenderer = $nodeRenderer;
    }

    public function setEnvironment(Environment $environment) : void
    {
        $this->environment = $environment;
    }

    public function getEnvironment() : ?Environment
    {
        return $this->environment;
    }

    public function render() : string
    {
        $renderedNode = new RenderedNode($this, $this->doRender());

        return $renderedNode->getRendered();
    }

    /**
     * @return Node|string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param Node|string|null $value
     */
    public function setValue($value) : void
    {
        $this->value = $value;
    }

    /**
     * @return string[]
     */
    public function getClasses() : array
    {
        return $this->classes;
    }

    public function getClassesString() : string
    {
        return implode(' ', $this->classes);
    }

    /**
     * @param string[] $classes
     */
    public function setClasses(array $classes) : void
    {
        $this->classes = $classes;
    }

    public function getValueString() : string
    {
        if ($this->value === null) {
            return '';
        }

        if ($this->value instanceof self) {
            return $this->value->getValueString();
        }

        return $this->value;
    }

    /**
     * @param string[] $lines
     */
    protected function normalizeLines(array $lines) : string
    {
        if ($lines !== []) {
            $firstLine = $lines[0];

            for ($k = 0; $k < strlen($firstLine); $k++) {
                if (trim($firstLine[$k]) !== '') {
                    break;
                }
            }

            foreach ($lines as &$line) {
                $line = substr($line, $k);
            }
        }

        return implode("\n", $lines);
    }

    protected function doRender() : string
    {
        return $this->getRenderer()->render($this);
    }

    protected function getRenderer() : NodeRenderer
    {
        if ($this->nodeRenderer === null) {
            throw new RuntimeException('A node should always have a node renderer assigned');
        }

        return $this->nodeRenderer;
    }
}
