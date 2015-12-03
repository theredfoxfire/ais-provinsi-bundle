<?php

namespace Ais\ProvinsiBundle\Handler;

use Ais\ProvinsiBundle\Model\ProvinsiInterface;

interface ProvinsiHandlerInterface
{
    /**
     * Get a Provinsi given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return ProvinsiInterface
     */
    public function get($id);

    /**
     * Get a list of Provinsis.
     *
     * @param int $limit  the limit of the result
     * @param int $offset starting from the offset
     *
     * @return array
     */
    public function all($limit = 5, $offset = 0);

    /**
     * Post Provinsi, creates a new Provinsi.
     *
     * @api
     *
     * @param array $parameters
     *
     * @return ProvinsiInterface
     */
    public function post(array $parameters);

    /**
     * Edit a Provinsi.
     *
     * @api
     *
     * @param ProvinsiInterface   $provinsi
     * @param array           $parameters
     *
     * @return ProvinsiInterface
     */
    public function put(ProvinsiInterface $provinsi, array $parameters);

    /**
     * Partially update a Provinsi.
     *
     * @api
     *
     * @param ProvinsiInterface   $provinsi
     * @param array           $parameters
     *
     * @return ProvinsiInterface
     */
    public function patch(ProvinsiInterface $provinsi, array $parameters);
}
