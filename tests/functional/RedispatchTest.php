<?php

namespace Unish;

use Symfony\Component\Filesystem\Path;

/**
 * @group base
 */
class RedispatchTest extends CommandUnishTestCase
{
    /**
     * Covers the following origin responsibilities.
     *  - A remote host is recognized in site specification.
     *  - Generates expected ssh command.
     */
    public function testDispatchUsingAlias()
    {
        $options = ['uri' => 'OMIT', 'alias-path' => Path::join(__DIR__, 'resources/alias-fixtures'), 'simulate' => null];
        $this->drush('status', [], $options, '@example.live');
        $this->assertStringContainsString("[notice] Simulating: ssh -o PasswordAuthentication=example www-admin@service-provider.com '/example/path/to/drush --no-interaction status --uri=https://example.com --root=/path/on/service-provider'", $this->getSimplifiedErrorOutput());
    }

    public function testNonExistentCommand()
    {
        // Assure that arguments and options are passed along to a command thats not recognized locally.
        $this->drush('non-existent-command', ['foo'], ['bar' => 'baz', 'simulate' => null], 'user@server/path/to/drupal#sitename');
        $output = $this->getErrorOutput();
        $this->assertStringContainsString('foo', $output);
        $this->assertStringContainsString('--bar=baz', $output);
        $this->assertStringContainsString('non-existent-command', $output);
    }
}
