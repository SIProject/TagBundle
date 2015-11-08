<?php
/*
 * (c) Suhinin Ilja <iljasuhinin@gmail.com>
 */
namespace SIP\TagBundle\Repository;

use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Query\Expr;
use DoctrineExtensions\Taggable\Taggable;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $qb;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var array
     */
    protected $select = array();

    /**
     * {@inheritdoc}
     */
    public function createQueryBuilder($alias)
    {
        $this->select = array();
        $this->qb = parent::createQueryBuilder($alias);
        $this->alias = $alias;

        return $this->qb;
    }

    /**
     * @param $alias
     */
    public function setSelect($alias)
    {
        array_push($this->select, $alias);

        if (!in_array($this->getAlias(), $this->select)) {
            array_push($this->select, $this->getAlias());
        }

        $this->qb->select($this->select);
    }

    /**
     * @param mixed $id
     * @param int $lockMode
     * @param null $lockVersion
     * @return array|object
     */
    public function findAll()
    {
        $this->createQueryBuilder('t');
        return $this->setTagging()->getQuery()->getResult();
    }

    public function setTagging()
    {
        $this->setSelect('t2');
        $this->qb->leftJoin("{$this->alias}.tagging", 't2');

        return $this;
    }

    /**
     * @param \DoctrineExtensions\Taggable\Taggable $resource
     * @return TagRepository
     */
    public function getTagsByResourceQuery(Taggable $resource)
    {
        $this->setSelect('t');
        $this->setSelect('t2');
        $this->qb->innerJoin('t.tagging', 't2', Expr\Join::WITH, 't2.resourceId = :id AND t2.resourceType = :type')
            ->setParameter('id', $resource->getTaggableId())
            ->setParameter('type', $resource->getTaggableType());

        return $this;
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getQuery()
    {
        $query = $this->qb->getQuery();

        return $query;
    }
    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }
}