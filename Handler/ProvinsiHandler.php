<?php

namespace Ais\ProvinsiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use Ais\ProvinsiBundle\Model\ProvinsiInterface;
use Ais\ProvinsiBundle\Form\ProvinsiType;
use Ais\ProvinsiBundle\Exception\InvalidFormException;

class ProvinsiHandler implements ProvinsiHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    /**
     * Get a Provinsi.
     *
     * @param mixed $id
     *
     * @return ProvinsiInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get a list of Provinsis.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    /**
     * Create a new Provinsi.
     *
     * @param array $parameters
     *
     * @return ProvinsiInterface
     */
    public function post(array $parameters)
    {
        $provinsi = $this->createProvinsi();

        return $this->processForm($provinsi, $parameters, 'POST');
    }

    /**
     * Edit a Provinsi.
     *
     * @param ProvinsiInterface $provinsi
     * @param array         $parameters
     *
     * @return ProvinsiInterface
     */
    public function put(ProvinsiInterface $provinsi, array $parameters)
    {
        return $this->processForm($provinsi, $parameters, 'PUT');
    }

    /**
     * Partially update a Provinsi.
     *
     * @param ProvinsiInterface $provinsi
     * @param array         $parameters
     *
     * @return ProvinsiInterface
     */
    public function patch(ProvinsiInterface $provinsi, array $parameters)
    {
        return $this->processForm($provinsi, $parameters, 'PATCH');
    }

    /**
     * Processes the form.
     *
     * @param ProvinsiInterface $provinsi
     * @param array         $parameters
     * @param String        $method
     *
     * @return ProvinsiInterface
     *
     * @throws \Ais\ProvinsiBundle\Exception\InvalidFormException
     */
    private function processForm(ProvinsiInterface $provinsi, array $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new ProvinsiType(), $provinsi, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);
        if ($form->isValid()) {

            $provinsi = $form->getData();
            $this->om->persist($provinsi);
            $this->om->flush($provinsi);

            return $provinsi;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createProvinsi()
    {
        return new $this->entityClass();
    }

}
