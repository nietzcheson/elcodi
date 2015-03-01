<?php

namespace Elcodi\Bundle\ProductBundle\EventListeners;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Elcodi\Component\Core\Services\Slugify;
use Elcodi\Component\Product\Entity\Interfaces\ProductInterface;

/**
 * Class ProductSlugEventListener
 */
class ProductSlugEventListener
{
    /**
     * @var Slugify
     *
     * Slugify service.
     */
    private $slugify;

    /**
     * Builds the class.
     *
     * @param Slugify $slugify the slugify service
     */
    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    /**
     * We must generate the slug if empty
     *
     * @param PreFlushEventArgs $args The pre flush event arguments.
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $entityManager       = $args->getEntityManager();
        $unitOfWork          = $entityManager
            ->getUnitOfWork();
        $scheduledInsertions = $unitOfWork
            ->getScheduledEntityInsertions();

        foreach ($scheduledInsertions as $entity) {

            if ($entity instanceof ProductInterface) {
                $currentSlug = $entity->getSlug();

                if (
                    '' == $currentSlug ||
                    is_null($currentSlug)
                ) {
                    $productName = $entity->getName();
                    $slug        = $this
                        ->slugify
                        ->transform($productName);

                    $entity->setSlug($slug);

                    $metaData = $entityManager
                        ->getClassMetadata(get_class($entity));

                    $unitOfWork
                        ->recomputeSingleEntityChangeSet(
                            $metaData,
                            $entity
                        );

                    $unitOfWork
                        ->computeChangeSets();
                }
            }
        }
    }
}
