<?php
namespace Cjel\TemplatesAide\Tests\Unit\Controller;

/**
 * Test case.
 *
 * @author Philipp Dieter <philippdieter@attic-media.net>
 */
class TranslationControllerTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Cjel\TemplatesAide\Controller\TranslationController
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder(\Cjel\TemplatesAide\Controller\TranslationController::class)
            ->setMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

}
