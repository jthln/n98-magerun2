<?php
/**
 * @todo    writing unit tests for toggling caches is complicated because environments differ
 *          touching caches changes state and will result in different test result second time
 *          solutions: disabling/enabling, faking&mocking or having one defined test environment
 */

namespace N98\Magento\Command\Cache;

use Symfony\Component\Console\Tester\CommandTester;
use N98\Magento\Command\PHPUnit\TestCase;

class DisableCommandTest extends TestCase
{
    const NONEXISTENT_CACHE_TYPE = 'FAKE_CACHE_TYPE';
    /**
     * @var $command ListCommand
     */
    protected $command = null;

    public function setUp()
    {
        $application = $this->getApplication();
        $application->add(new DisableCommand);
        $application->add(new ListCommand);

        $this->command = $this->getApplication()->find('cache:disable');
    }

    public function testDisableNonexistentCache()
    {
        $commandTester = new CommandTester($this->command);
        $commandTester->execute(array('command' => $this->command->getName(), 'type' => self::NONEXISTENT_CACHE_TYPE));

        // Strip tags because of console formatting (<info> etc)
        $expectedOutput = strip_tags(
            sprintf(DisableCommand::INVALID_TYPES_MESSAGE, self::NONEXISTENT_CACHE_TYPE) .
            PHP_EOL .
            DisableCommand::ABORT_MESSAGE .
            PHP_EOL
        );

        $this->assertEquals($expectedOutput, $commandTester->getDisplay());
    }
}
