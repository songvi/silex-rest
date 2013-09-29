<?php
namespace MJanssen\Provider;

use Silex\Application;
use JMS\Serializer\SerializerBuilder;

class ServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test if services are registered
     */
    public function testIfServicesAreRegistered()
    {
        $app = $this->getMockApplication();

        $this->assertTrue(isset($app['serializer']));
        $this->assertTrue(isset($app['doctrine.extractor']));
        $this->assertTrue(isset($app['doctrine.hydrator']));
        $this->assertTrue(isset($app['doctrine.resolver']));
        $this->assertTrue(isset($app['service.validator']));
        $this->assertTrue(isset($app['service.request.validator']));
        $this->assertFalse(isset($app['foo']));
    }

    /**
     * Test if extractor service can be instantiated
     */
    public function testExtractorService()
    {
        $app = $this->getMockApplication();

        $app['serializer'] = $app->share(function($app) {
            return SerializerBuilder::create()->build();
        });

        $this->assertInstanceOf('MJanssen\Service\ExtractorService', $app['doctrine.extractor']);
    }

    /**
     * Test if extractor service can be instantiated
     */
    public function testHydratorService()
    {
        $app = $this->getMockApplication();

        $app['serializer'] = $app->share(function($app) {
            return SerializerBuilder::create()->build();
        });

        $this->assertInstanceOf('MJanssen\Service\HydratorService', $app['doctrine.hydrator']);
    }

    /**
     * Test if resolver service can be instantiated
     */
    public function testResolverService()
    {
        $app = $this->getMockApplication();

        $app['orm.em'] = $this->getMock('\Doctrine\ORM\EntityManager',
            array('getRepository', 'getClassMetadata', 'persist', 'flush'), array(), '', false);

        $this->assertInstanceOf('MJanssen\Service\ResolverService', $app['doctrine.resolver']);
    }

    /**
     * Get a default silex application
     * @return Application
     */
    protected function getMockApplication()
    {
        $app = new Application();

        $app->register(
            new ServiceProvider()
        );

        return $app;
    }


}