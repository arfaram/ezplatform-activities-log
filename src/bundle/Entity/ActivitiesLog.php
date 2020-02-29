<?php

namespace EzPlatform\ActivitiesLogBundle\Entity;

/**
 * ActivitiesLog.
 */
class ActivitiesLog
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $contentObjectId;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var string
     */
    private $data;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contentObjectId.
     *
     * @param int $contentObjectId
     *
     * @return ActivitiesLog
     */
    public function setContentobjectId($contentObjectId)
    {
        $this->contentObjectId = $contentObjectId;

        return $this;
    }

    /**
     * Get contentObjectId.
     *
     * @return int
     */
    public function getContentobjectId()
    {
        return $this->contentObjectId;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return ActivitiesLog
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set eventName.
     *
     * @param string $eventName
     *
     * @return ActivitiesLog
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName.
     *
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Set data.
     *
     * @param string $data
     *
     * @return ActivitiesLog
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
