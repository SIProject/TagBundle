<?php
/*
 * (c) Suhinin Ilja <iljasuhinin@gmail.com>
 */
namespace SIP\TagBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

class BaseTagAdmin extends Admin
{
    /**
     * @var \SIP\TagBundle\Form\DataTransformer\TagTransformer
     */
    protected $tagTransformer;

    /**
     * @return \Symfony\Component\Form\FormBuilder
     */
    public function getFormBuilder()
    {
        $formBuilder = parent::getFormBuilder();
        $formBuilder->addModelTransformer($this->getTagTransformer());
        return $formBuilder;
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function postUpdate($object)
    {
        parent::postUpdate($object);
        $this->getTagTransformer()->postPersist($object);
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function postPersist($object)
    {
        $this->getTagTransformer()->postPersist($object);
    }

    /**
     * @return object|\SIP\TagBundle\Form\DataTransformer\TagTransformer
     */
    public function getTagTransformer()
    {
        if (!$this->tagTransformer) {
            $container = $this->getConfigurationPool()->getContainer();
            $this->tagTransformer = $container->get('sip.tag_transformer');
        }

        return $this->tagTransformer;
    }
}