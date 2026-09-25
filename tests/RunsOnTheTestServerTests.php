<?php

namespace Cviebrock\EloquentTaggable\Test;

use Illuminate\Support\Facades\DB;

/**
 * The connection the tests use is the one on 3310.
 *
 * Asks the server the connection reached which port it is, rather than reading
 * the configured one back.
 *
 * @internal
 */
class RunsOnTheTestServerTests extends TestCase
{
    public function testTheTestConnectionIsOnTheTestServer(): void
    {
        if (getenv('GITHUB_ACTIONS') === 'true') {
            self::markTestSkipped('GitHub Actions runs the tests on the MySQL service of its own job.');
        }

        self::assertSame('127.0.0.1', config('database.connections.mysql.host'));
        self::assertSame(3310, (int) config('database.connections.mysql.port'));
        self::assertSame(3310, (int) DB::selectOne('select @@port as port')->port);
    }
}
