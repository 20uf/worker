<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Worker\Consumer\Mapper;

use Worker\Consumer\Model\Source;

/**
 * Class SourceMapper
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class SourceMapper
{
    /**
     * Maps from rabbitmq source to worker source model.
     *
     * @param string $source The retrieved source from rabbitmq.
     *
     * @return Source
     */
    public function map($source = null)
    {
        if (isset($source) && strlen($source) > 0) {
            $decodedSource = json_decode($source, true);

            if (isset($decodedSource) && is_array($decodedSource)) {
                // todo :)
                return new Source();
            }
        }

        throw new \RuntimeException('Source is empty.');
    }
}
