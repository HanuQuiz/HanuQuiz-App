<?php
/**
 * UserFrosting (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/UserFrosting
 * @copyright Copyright (c) 2019 Alexander Weissman
 * @license   https://github.com/userfrosting/UserFrosting/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Core\Tests\Integration;

use UserFrosting\Sprinkle\Core\Tests\TestDatabase;
use UserFrosting\Tests\TestCase;

/**
 *    Tests for the Migrator Service
 *
 *    @author Louis Charette
 */
class DatabaseMigratorServiceTest extends TestCase
{
    use TestDatabase;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        // Setup test database
        $this->setupTestDatabase();
    }

    public function testMigratorService()
    {
        $this->assertInstanceOf('UserFrosting\Sprinkle\Core\Database\Migrator\Migrator', $this->ci->migrator);
    }
}
