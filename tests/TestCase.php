<?php

namespace Cviebrock\EloquentTaggable\Test;

use Cviebrock\EloquentTaggable\ServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class TestCase.
 */
abstract class TestCase extends Orchestra
{
    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->beforeApplicationDestroyed(static function () {
            (new \CreateTestModelsTable())->down();
            // @phpstan-ignore-next-line class.notFound
            (new \CreateTaggableTable())->down();
        });
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->createTestDatabase();
    }

    /**
     * The test database lives on the test MySQL server, 127.0.0.1:3310 (see
     * phpunit.xml), and is made there the first time it is needed. Anywhere
     * else, GitHub Actions passing the port of its own service for one, it
     * already exists.
     */
    private function createTestDatabase(): void
    {
        $database = (string) getenv('DB_DATABASE');

        if (getenv('DB_HOST') !== '127.0.0.1' || getenv('DB_PORT') !== '3310' || $database === '') {
            return;
        }

        try {
            $pdo = new \PDO(
                'mysql:host=127.0.0.1;port=3310',
                (string) getenv('DB_USERNAME'),
                (string) getenv('DB_PASSWORD'),
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_TIMEOUT => 3]
            );
            $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '', $database) . '`');
        } catch (\Throwable) {
            // Not answering. The first test that needs the database reports it.
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            TestServiceProvider::class,
        ];
    }

    /**
     * Custom test to see if two arrays have the same values, regardless
     * of indices or order.
     */
    protected static function assertArrayValuesAreEqual(array $expected, array $actual): void
    {
        self::assertCount(count($expected), $actual);
        self::assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * Helper to generate a test model.
     */
    protected function newModel(array $data = ['title' => 'test']): TestModel
    {
        return TestModel::create($data);
    }

    /**
     * Helper to generate a test dummy model.
     */
    protected function newDummy(array $data = ['title' => 'dummy']): TestDummy
    {
        return TestDummy::create($data);
    }

    /**
     * Set up the database.
     */
    private function setUpDatabase(): void
    {
        include_once __DIR__ . '/../resources/database/migrations/create_taggable_table.php.stub';
        // @phpstan-ignore-next-line class.notFound
        (new \CreateTaggableTable())->up();

        include_once __DIR__ . '/database/migrations/2013_11_04_163552_create_test_models_table.php';
        (new \CreateTestModelsTable())->up();
    }
}
