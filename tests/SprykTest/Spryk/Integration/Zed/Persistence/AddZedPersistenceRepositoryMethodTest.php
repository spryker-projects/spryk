<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykTest\Spryk\Integration\Zed\Persistence;

use Codeception\Test\Unit;
use SprykTest\Module\ClassName;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Spryk
 * @group Integration
 * @group Zed
 * @group Persistence
 * @group AddZedPersistenceRepositoryMethodTest
 * Add your own group annotations below this line
 */
class AddZedPersistenceRepositoryMethodTest extends Unit
{
    /**
     * @var \SprykTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsZedPersistenceRepositoryMethod(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--method' => 'doSomething',
            '--input' => 'string $fooBar',
            '--output' => 'array',
        ]);

        $this->tester->assertClassHasMethod(ClassName::ZED_REPOSITORY, 'doSomething');
    }
}
