<?php

namespace Ais\ProvinsiBundle\Model;

Interface ProvinsiInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set nama
     *
     * @param string $nama
     *
     * @return Provinsi
     */
    public function setNama($nama);

    /**
     * Get nama
     *
     * @return string
     */
    public function getNama();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Provinsi
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive();

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Provinsi
     */
    public function setIsDelete($isDelete);

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
