<?php

declare(strict_types=1);

namespace Lemon\Tests\Tree;

use Lemon\Kernel\Application;
use Lemon\Support\Filesystem;
use Lemon\Tests\TestCase;
use Lemon\Tree\Manager;

class ManagerTest extends TestCase
{
    public function tearDown(): void
    {
        if (Filesystem::isDir(__DIR__ . '/storage')) {
            Filesystem::delete(__DIR__ . '/storage');
        }
    }

    public function getManager(): Manager
    {
        $app = new Application(__DIR__);

        return new Manager($app);
    }

    public function testSaveToEmptyFile(): void
    {
        $manager = $this->getManager();

        $entity = new FooEntity(1, 'foo');

        $manager->save($entity);
        
        unset($manager);

        $this->assertFileEquals(
            __DIR__ . '/fixtures/foo_entity.php',
            __DIR__ . '/storage/tree/entity_lemon_tests_tree_foo_entity.php'
        );
    }

    public function testFindInExistingFile(): void
    {
        // this test expects the file to exist, so we simply copy the fixture 
        touch(__DIR__ . '/storage/tree/entity_lemon_tests_tree_foo_entity.php');
        copy(
            __DIR__ . '/fixtures/foo_entity.php',
            __DIR__ . '/storage/tree/entity_lemon_tests_tree_foo_entity.php'
        );

        $manager = $this->getManager();

        $entity = $manager->find(FooEntity::class, 'id', 1);

        $this->assertInstanceOf(FooEntity::class, $entity);
        $this->assertEquals(1, $entity->id);
        $this->assertEquals('foo', $entity->name);
    }

    public function testAll(): void
    {
        $manager = $this->getManager();

        $entities = $manager->all(FooEntity::class);

        $this->assertThat($entities, $this->equalTo([
            new FooEntity(1, 'foo'),
        ]));
    }
}

class FooEntity
{
    public function __construct(
        public int $id,
        public string $name
    ) {

    }
}
