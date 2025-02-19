<?php

declare(strict_types=1);

namespace Unish;

use Drush\Drupal\Commands\pm\PmCommands;
use Symfony\Component\Filesystem\Path;

/**
 * @group commands
 *
 */
class ModuleDrushCommandTest extends CommandUnishTestCase
{
    use TestModuleHelperTrait;

    /**
     * Tests command info alter.
     */
    public function testDrushCommandsInModule()
    {
        $this->setUpDrupal(1, true);
        // Commands in the woot module should not be available yet,
        // because the woot module has not been installed.
        $this->drush('woot', [], [], null, null, self::EXIT_ERROR);
        $this->drush('woot-factory', [], [], null, null, self::EXIT_ERROR);

        // Install the woot module; this should make the commands available
        $this->setupModulesForTests(['woot'], Path::join(__DIR__, '/../fixtures/modules'));
        $this->drush('pm-install', ['woot']);

        $this->drush('woot', [], []);
        $this->assertStringContainsString('Woot!', $this->getOutput());

        $this->drush('woot-factory', [], []);
        $this->assertStringContainsString('Woot 55', $this->getOutput());
    }
}
