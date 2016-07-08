<?php

/*
 * This file is part of the Worker project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Worker\Consumer\Model;

/**
 * The model for source.
 *
 * @author Michael Coulleret <michael@coulleret.pro>
 */
class Source
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var integer
     */
    protected $broadcastId;

    /**
     * @var integer
     */
    protected $broadcastTotalModerator;

    /**
     * Setter for id.
     *
     * @param integer $id The field id for current model.
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Getter for id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter for type.
     *
     * @param string $type The field type for current model.
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Getter for type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Setter for url.
     *
     * @param string $url The field url for current model.
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Getter for url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Setter for broadcastId.
     *
     * @param integer $broadcastId The field broadcastId for current model.
     *
     * @return self
     */
    public function setBroadcastId($broadcastId)
    {
        $this->broadcastId = $broadcastId;

        return $this;
    }

    /**
     * Getter for broadcastId.
     *
     * @return integer
     */
    public function getBroadcastId()
    {
        return $this->broadcastId;
    }

    /**
     * Setter for broadcastTotalModerator.
     *
     * @param integer $broadcastTotalModerator The field broadcastTotalModerator for current model.
     *
     * @return self
     */
    public function setBroadcastTotalModerator($broadcastTotalModerator)
    {
        $this->broadcastTotalModerator = $broadcastTotalModerator;

        return $this;
    }

    /**
     * Getter for broadcastTotalModerator.
     *
     * @return integer
     */
    public function getBroadcastTotalModerator()
    {
        return $this->broadcastTotalModerator;
    }
}
