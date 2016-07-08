<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Common\Parser;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Parser for yaml files.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class YamlParser
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var string
     */
    protected $environment;

    /**
     * YamlParser constructor.
     *
     * @param string $environment
     */
    public function __construct($environment = 'prod')
    {
        $this->parser       = new Parser();
        $this->environment  = $environment;
    }

    /**
     * Parses the content of the file.
     *
     * @return array
     *
     * @throws ParseException Throw Exception when there's a parsing error.
     */
    public function parse()
    {
        $file = sprintf('%s/../../../../app/config/parameters%s.yml', __DIR__, $this->getEnvironment());

        try {
            $options = $this->parser->parse(file_get_contents($file));
            if (array_key_exists('parameters', $options)) {
                return $options['parameters'];
            }
            throw \RuntimeException('parameter file is malformed.');
        } catch (ParseException $exception) {
            /**
             * @todo log exception.
             */
            throw $exception;
        }
    }

    /**
     * Gets the environment.
     *
     * @return string The current environment
     */
    private function getEnvironment()
    {
        return $this->environment == 'prod' ? '' : '_'.$this->environment;
    }
}
