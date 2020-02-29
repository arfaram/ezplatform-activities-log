<?php

namespace EzPlatform\ActivitiesLogBundle\Twig;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Repository as RepositoryInterface;
use Psr\Log\LoggerInterface;
use Twig_Extension;
use Twig_SimpleFunction;

class ActivitiesLogExtension extends Twig_Extension
{
    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    public function __construct(
        RepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'activitieslog.extension';
    }

    /**
     * @return array|\Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction(
                'data_unserialize',
                array($this, 'dataUnserialize')
            ),
            new Twig_SimpleFunction(
                'check_value_type',
                array($this, 'checkValueType')
            ),
            new Twig_SimpleFunction(
                'get_content',
                array($this, 'getUserperID')
            ),
        );
    }

    /**
     * @param $str
     * @return array
     */
    public function dataUnserialize($str)
    {
        return  (array) unserialize($str, ['allowed_classes' => false]);
    }

    /**
     * @param $value
     * @return bool|string
     */
    public function checkValueType($value)
    {
        switch (\gettype($value)) {
            case 'integer':
            case 'NULL':
            case 'string':
                return $value;
            case 'array':
                return 'array';
            case 'object':
                //TODO e.g Empty trash
                return false;
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function getUserperID($id)
    {
        return $this->repository->sudo(
            function (RepositoryInterface $repository) use ($id) {
                try {
                    return $repository->getContentService()->loadContent($id);
                } catch (NotFoundException $exception) {
                    //user has been deleted
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
