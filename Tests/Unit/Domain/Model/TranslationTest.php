<?php
namespace Cjel\TemplatesAide\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Philipp Dieter <philippdieter@attic-media.net>
 */
class TranslationTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Cjel\TemplatesAide\Domain\Model\Translation
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Cjel\TemplatesAide\Domain\Model\Translation();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function dummyTestToNotLeaveThisFileEmpty()
    {
        self::markTestIncomplete();
    }
}
