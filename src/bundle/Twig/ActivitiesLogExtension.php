<?php

namespace EzPlatform\ActivitiesLogBundle\Twig;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Repository as RepositoryInterface;
use Psr\Log\LoggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ActivitiesLogExtension extends AbstractExtension
{
    private RepositoryInterface $repository;
    private LoggerInterface $logger;

    public function __construct(
        RepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->logger     = $logger;
    }

    public function getName(): string
    {
        return 'activitieslog.extension';
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'data_unserialize',
                [$this, 'dataUnserialize']
            ),
            new TwigFunction(
                'check_value_type',
                [$this, 'checkValueType']
            ),
            new TwigFunction(
                'get_content',
                [$this, 'getUserperID']
            ),
        ];
    }

    public function dataUnserialize($str): array
    {
        return (array) unserialize($str, ['allowed_classes' => false]);
    }

    public function checkValueType($value): bool|string
    {
        switch (\gettype($value)) {
            case 'integer':
            case 'NULL':
            case 'string':
                return $value;
            case 'array':
                return 'array';
            case 'object':
                // TODO e.g Empty trash
                return false;
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    public function getUserperID($id)
    {
        return $this->repository->sudo(
            function (RepositoryInterface $repository) use ($id) {
                try {
                    return $repository->getContentService()->loadContent($id);
                } catch (NotFoundException $exception) {
                    // user has been deleted
                    $this->logger->warning(sprintf(
                        'Unable to fetch creator content for contentId %s, (original exception: %s)',
                        $id,
                        $exception->getMessage()
                    ));
                }
            }
        );
    }
}
