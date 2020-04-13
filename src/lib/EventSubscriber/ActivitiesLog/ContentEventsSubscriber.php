<?php

namespace EzPlatform\ActivitiesLog\EventSubscriber\ActivitiesLog;

use eZ\Publish\API\Repository\Events\Content\CopyContentEvent;
use eZ\Publish\API\Repository\Events\Content\CreateContentDraftEvent;
use eZ\Publish\API\Repository\Events\Content\DeleteContentEvent;
use eZ\Publish\API\Repository\Events\Content\DeleteVersionEvent;
use eZ\Publish\API\Repository\Events\Content\HideContentEvent;
use eZ\Publish\API\Repository\Events\Content\PublishVersionEvent;
use eZ\Publish\API\Repository\Events\Content\RevealContentEvent;
use eZ\Publish\API\Repository\Events\Content\UpdateContentEvent;

/**
 * Class ContentEventsSubscriber.
 */
final class ContentEventsSubscriber extends AbstractSubscriber
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            HideContentEvent::class => ['onHideContent'],
            RevealContentEvent::class => ['onRevealContent'],
            CopyContentEvent::class => ['onCopyContent'],
            DeleteVersionEvent::class => ['onDeleteVersionEvent'],
            DeleteContentEvent::class => ['onDeleteContentEvent'],
            CreateContentDraftEvent::class => ['onCreateContentDraftEvent'],
            UpdateContentEvent::class => ['onUpdateContentEvent'],
            PublishVersionEvent::class => 'onPublishVersionEvent',
        ];
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Content\HideContentEvent $event
     * @throws \Exception
     */
    public function onHideContent(HideContentEvent $event)
    {
        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $event->getContentInfo()->name,
                    'contentId' => $event->getContentInfo()->id,
                    'currentVersionNo' => $event->getContentInfo()->currentVersionNo,
                ]))
            ->setContentobjectId($event->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Content\RevealContentEvent $event
     * @throws \Exception
     */
    public function onRevealContent(RevealContentEvent $event)
    {
        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $event->getContentInfo()->name,
                    'contentId' => $event->getContentInfo()->id,
                    'currentVersionNo' => $event->getContentInfo()->currentVersionNo,
                ]))
            ->setContentobjectId($event->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Content\CopyContentEvent $event
     * @throws \Exception
     */
    public function onCopyContent(CopyContentEvent $event)
    {
        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $event->getContentInfo()->name,
                    'contentId' => $event->getContentInfo()->id,
                    'mainLocationId' => $event->getContentInfo()->mainLocationId,
                    'destination' => $event->getDestinationLocationCreateStruct()->parentLocationId,
                ]))
            ->setContentobjectId($event->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Content\DeleteContentEvent $event
     * @throws \Exception
     */
    public function onDeleteContentEvent(DeleteContentEvent $event)
    {
        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $event->getContentInfo()->name,
                    'contentId' => $event->getContentInfo()->id,
                    'locations' => implode(',', $event->getLocations()),
                ]))
            ->setContentobjectId($event->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }

    /**
     * @todo Bug: delete multiple version trigger only the last deleted one
     * @param \eZ\Publish\API\Repository\Events\Content\DeleteVersionEvent $event
     * @throws \Exception
     */
    public function onDeleteVersionEvent(DeleteVersionEvent $event): void
    {
        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $event->getVersionInfo()->getContentInfo()->name,
                    'contentId' => $event->getVersionInfo()->getContentInfo()->id,
                    'language' => $event->getVersionInfo()->getInitialLanguage()->name,
                    'version' => $event->getVersionInfo()->versionNo,
                ]))
            ->setContentobjectId($event->getVersionInfo()->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Content\CreateContentDraftEvent $event
     * @throws \Exception
     */
    public function onCreateContentDraftEvent(CreateContentDraftEvent $event): void
    {
        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $event->getContentDraft()->getVersionInfo()->getContentInfo()->name,
                    'contentId' => $event->getContentInfo()->id,
                    'language' => $event->getContentDraft()->getVersionInfo()->getInitialLanguage()->name,
                    'version' => $event->getContentDraft()->getVersionInfo()->versionNo,
                ]))
            ->setContentobjectId($event->getContentDraft()->getVersionInfo()->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Content\UpdateContentEvent $event
     * @throws \Exception
     */
    public function onUpdateContentEvent(UpdateContentEvent $event): void
    {
        $content = $event->getContent();
        $contentName = $content->getVersionInfo()->getContentInfo()->name;
        $contentId = $content->getVersionInfo()->getContentInfo()->id;
        $versionNo = $content->getVersionInfo()->versionNo;
        $currentLanguage = $content->getVersionInfo()->getInitialLanguage()->name;

        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $contentName,
                    'contentId' => $contentId,
                    'language' => $currentLanguage,
                    'version' => $versionNo,
                ]))
            ->setContentobjectId($event->getVersionInfo()->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Content\PublishVersionEvent $event
     * @throws \Exception
     */
    public function onPublishVersionEvent(PublishVersionEvent $event): void
    {
        $content = $event->getContent();
        $contentName = $content->getVersionInfo()->getContentInfo()->name;
        $contentId = $content->getVersionInfo()->getContentInfo()->id;
        $versionNo = $content->getVersionInfo()->versionNo;
        $contentLanguages = $content->getVersionInfo()->languageCodes;
        $currentLanguage = $content->getVersionInfo()->getInitialLanguage()->name;

        $this->activitiesLog
            ->setData(serialize(
                [
                    'name' => $contentName,
                    'contentId' => $contentId,
                    'published language' => $currentLanguage,
                    'version' => $versionNo,
                    'languages' => implode(',', $contentLanguages),
                ]))
            ->setContentobjectId($event->getVersionInfo()->getContentInfo()->id);

        $this->setDefaultData($event)->persistData();
    }
}
