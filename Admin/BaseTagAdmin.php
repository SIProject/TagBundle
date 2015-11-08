<?php
/*
 * (c) Suhinin Ilja <iljasuhinin@gmail.com>
 */
namespace SIP\TagBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;

class BaseTagAdmin extends Admin
{
    /**
     * @var \SIP\ResourceBundle\Form\DataTransformer\TagTransformer
     */
    protected $tagTransformer;

    /**
     * @return \Symfony\Component\Form\FormBuilder
     */
    public function getFormBuilder()
    {
        $container = $this->getConfigurationPool()->getContainer();
        $this->tagTransformer = $container->get('sip.tag_transformer');

        $formBuilder = parent::getFormBuilder();
        $formBuilder->addModelTransformer($this->tagTransformer);
        return $formBuilder;
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function postUpdate($object)
    {
        parent::postUpdate($object);
        $this->tagTransformer->postPersist($object);
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function postPersist($object)
    {
        $this->tagTransformer->postPersist($object);
    }
}