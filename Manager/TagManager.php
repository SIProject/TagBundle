<?php
/*
 * (c) Suhinin Ilja <iljasuhinin@gmail.com>
 */
namespace SIP\TagBundle\Manager;

use DoctrineExtensions\Taggable\TagManager as BaseTagManager;
use DoctrineExtensions\Taggable\Taggable;
use Doctrine\ORM\Query\Expr;

class TagManager extends BaseTagManager
{
    /**
     * Loads all tags for the given taggable resource
     *
     * @param Taggable  $resource   Taggable resource
     */
    public function loadTagging(Taggable $resource)
    {
        $tags = $this->getTagging($resource);
        $this->replaceTags($tags, $resource);
    }

    /**
     * @param \DoctrineExtensions\Taggable\Taggable $resource
     * @return array
     */
    protected function getTagging(Taggable $resource)
    {
        $repository = $this->getTagRepository();
        $repository->createQueryBuilder('t');

        return $repository->getTagsByResourceQuery($resource)->getQuery()->getResult();
    }

    /**
     * @param int|array $tags
     * @param string $class
     * @return array
     */
    public function getObjectsByTags($tags, $class)
    {
        $qb = $this->em->createQueryBuilder();
        if (is_array($tags)) {
            $tagClause = $qb->expr()->in('t.tag', $tags);
        } else {
            $tagClause = 't.tag = :tag';
            $qb->setParameter('tag', $tags);
        }

        $tagging = $qb->select('t')
                      ->from($this->taggingClass, 't')
                      ->andWhere($tagClause)
                      ->andWhere('t.resourceType = :resourceType')
                      ->setParameter('resourceType', $class)->getQuery()->getResult();

        $output = array();
        foreach ($tagging as $taggingObject) {
            array_push($output, $taggingObject->getResourceId());
        }

        return $output;
    }

    /**
     * @return \SIP\ResourceBundle\Repository\TagRepository
     */
    public function getTagRepository()
    {
        return $this->em->getRepository($this->tagClass);
    }
}
