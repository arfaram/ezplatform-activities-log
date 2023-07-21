<?php

namespace EzPlatform\ActivitiesLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use EzPlatform\ActivitiesLogBundle\Repository\ActivitiesLogRepository;

/**
 * @ORM\Entity(repositoryClass=ActivitiesLogRepository::class)
 */
class ActivitiesLog
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="contentobject_id", type="integer", nullable=true)
     */
    private $contentObjectId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="event_name", type="string", length=255)
     */
    private $eventName;

    /**
     * @ORM\Column(type="text")
     */
    private $data;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set contentObjectId.
     *
     * @param int $contentObjectId
     */
    public function setContentobjectId($contentObjectId): self
    {
        $this->contentObjectId = $contentObjectId;

        return $this;
    }

    /**
     * Get contentObjectId.
     *
     * @return int
     */
    public function getContentobjectId(): ?int
    {
        return $this->contentObjectId;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     */
    public function setUserId($userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set eventName.
     *
     * @param string $eventName
     */
    public function setEventName($eventName): self
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * Get eventName.
     *
     * @return string
     */
    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    /**
     * Set data.
     *
     * @param string $data
     */
    public function setData($data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return string
     */
    public function getData(): ?string
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
