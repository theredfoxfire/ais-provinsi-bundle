<?php

namespace Ais\ProvinsiBundle\Tests\Handler;

use Ais\ProvinsiBundle\Handler\ProvinsiHandler;
use Ais\ProvinsiBundle\Model\ProvinsiInterface;
use Ais\ProvinsiBundle\Entity\Provinsi;

class ProvinsiHandlerTest extends \PHPUnit_Framework_TestCase
{
    const DOSEN_CLASS = 'Ais\ProvinsiBundle\Tests\Handler\DummyProvinsi';

    /** @var ProvinsiHandler */
    protected $provinsiHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }
        
        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::DOSEN_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::DOSEN_CLASS));
    }


    public function testGet()
    {
        $id = 1;
        $provinsi = $this->getProvinsi();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($provinsi));

        $this->provinsiHandler = $this->createProvinsiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $this->provinsiHandler->get($id);
    }

    public function testAll()
    {
        $offset = 1;
        $limit = 2;

        $provinsis = $this->getProvinsis(2);
        $this->repository->expects($this->once())->method('findBy')
            ->with(array(), null, $limit, $offset)
            ->will($this->returnValue($provinsis));

        $this->provinsiHandler = $this->createProvinsiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);

        $all = $this->provinsiHandler->all($limit, $offset);

        $this->assertEquals($provinsis, $all);
    }

    public function testPost()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $provinsi = $this->getProvinsi();
        $provinsi->setTitle($title);
        $provinsi->setBody($body);

        $form = $this->getMock('Ais\ProvinsiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($provinsi));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->provinsiHandler = $this->createProvinsiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $provinsiObject = $this->provinsiHandler->post($parameters);

        $this->assertEquals($provinsiObject, $provinsi);
    }

    /**
     * @expectedException Ais\ProvinsiBundle\Exception\InvalidFormException
     */
    public function testPostShouldRaiseException()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $provinsi = $this->getProvinsi();
        $provinsi->setTitle($title);
        $provinsi->setBody($body);

        $form = $this->getMock('Ais\ProvinsiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->provinsiHandler = $this->createProvinsiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $this->provinsiHandler->post($parameters);
    }

    public function testPut()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('title' => $title, 'body' => $body);

        $provinsi = $this->getProvinsi();
        $provinsi->setTitle($title);
        $provinsi->setBody($body);

        $form = $this->getMock('Ais\ProvinsiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($provinsi));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->provinsiHandler = $this->createProvinsiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $provinsiObject = $this->provinsiHandler->put($provinsi, $parameters);

        $this->assertEquals($provinsiObject, $provinsi);
    }

    public function testPatch()
    {
        $title = 'title1';
        $body = 'body1';

        $parameters = array('body' => $body);

        $provinsi = $this->getProvinsi();
        $provinsi->setTitle($title);
        $provinsi->setBody($body);

        $form = $this->getMock('Ais\ProvinsiBundle\Tests\FormInterface'); //'Symfony\Component\Form\FormInterface' bugs on iterator
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($provinsi));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->provinsiHandler = $this->createProvinsiHandler($this->om, static::DOSEN_CLASS,  $this->formFactory);
        $provinsiObject = $this->provinsiHandler->patch($provinsi, $parameters);

        $this->assertEquals($provinsiObject, $provinsi);
    }


    protected function createProvinsiHandler($objectManager, $provinsiClass, $formFactory)
    {
        return new ProvinsiHandler($objectManager, $provinsiClass, $formFactory);
    }

    protected function getProvinsi()
    {
        $provinsiClass = static::DOSEN_CLASS;

        return new $provinsiClass();
    }

    protected function getProvinsis($maxProvinsis = 5)
    {
        $provinsis = array();
        for($i = 0; $i < $maxProvinsis; $i++) {
            $provinsis[] = $this->getProvinsi();
        }

        return $provinsis;
    }
}

class DummyProvinsi extends Provinsi
{
}
