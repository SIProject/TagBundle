<?php
/*
 * (c) Suhinin Ilja <iljasuhinin@gmail.com>
 */
namespace SIP\TagBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineExtensions\Taggable\TagManager;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @var TagManager
     */
    private $tagManager;

    /**
     * @param ObjectManager $om
     */
    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function transform($object)
    {
        if (null === $object) {
            return $object;
        }

        $this->tagManager->loadTagging($object);

        return $object;
    }

    public function reverseTransform($object)
    {
        if (!$object) {
            return $object;
        }

        return $object;
    }

    // Todo: Move this out into a manager class
    public function postPersist($object)
    {
        $this->tagManager->saveTagging($object);
    }
}