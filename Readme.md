# All.In Data Micro Core 
## A Micro Wordpress Plugin Framework

The Core module of the All.In Data Micro Plugin Framework provides fundamental and 
reusable functionality for the modern development of Wordpress plugins.


## Installment
### Requirements

Create a new plugin folder in your wordpress project, e.g. `foobar-wp`.
Use the terminal inside the folder and run:

    composer require allindata/wordpress-micro-core
    
Create the plugin bootstrap file `foobar-wp.php` with the following (minimal) content:

```
<?php

declare(strict_types=1);

/**
 * Plugin Name: Foobar Wordpress
 * Description: Foobar Wordpress
 * Version: 1.0
 * Author: All.In Data GmbH
 * Author URI: https://www.all-in-data.de
 * Text Domain: hfoobar-wp
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 5.0.0
 * WC tested up to: 5.3.0
 */

defined('ABSPATH') or exit;

require __DIR__ . '/vendor/autoload.php';

class FoobarWp extends \AllInData\Micro\Core\Init
{
    const PLUGIN_CONFIGURATION = \FoobarWp\PluginConfiguration::class;
    const SLUG = 'foobar-wp';
    const VERSION = '1.0';
    const TEMPLATE_DIR = __DIR__ . '/view/';
    const TEMP_DIR = ABSPATH . 'tmp/';
    const FILE = __FILE__;
}
add_action('allindata/micro/core/init', [FoobarWp::class, 'init']);
```

### Plugin Configuration (Dependency Injection)
The plugin framework supports the [Disco](https://github.com/bitExpert/disco) Dependecy Injection container, for this the configuration class 
`PluginConfiguration.php` will be created in the folder src:

```
<?php

declare(strict_types=1);

namespace FoobarWp;

use AllInData\Micro\Core\Controller\PluginControllerInterface;
use AllInData\Micro\Core\Module\PluginModuleInterface;
use AllInData\Micro\Core\ShortCode\PluginShortCodeInterface;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;

/**
 * Class PluginConfiguration
 * @package FoobarWp
 * @Configuration
 */
class PluginConfiguration
{
    /**
     * @Bean
     */
    public function PluginApp(): Plugin
    {
        return new Plugin(
            \FoobarWp::load()->getTemplateDirectory(),
            $this->getPluginModules(),
            $this->getPluginControllers(),
            $this->getPluginShortCodes()
        );
    }

    /**
     * @return PluginModuleInterface[]
     */
    private function getPluginModules(): array
    {
        return [];
    }

    /**
     * @return PluginControllerInterface[]
     */
    private function getPluginControllers(): array
    {
        return [];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes(): array
    {
        return [];
    }
}
```

### Plugin Class (Application Entrypoint)
```
<?php

declare(strict_types=1);

namespace FoobarWp;

use AllInData\Micro\Core\AbstractPlugin;
use AllInData\Micro\Core\PluginInterface;

class Plugin extends AbstractPlugin implements PluginInterface
{
    public function load()
    {
        // some examples
        add_action('wp_enqueue_scripts', [$this, 'addScripts'], 999);
        add_action('admin_enqueue_scripts', [$this, 'addAdminScripts'], 999);
        add_action('admin_enqueue_scripts', [$this, 'addAdminStyles'], 999);
    }

    public function addAdminStyles()
    {
        // ...
    }

    public function addScripts()
    {
        // ...
    }

    public function addAdminScripts()
    {
        // ...
    }
}
```

This should be enough as a scaffold to start your plugin development.

## Contribution
Feel free to contribute to this library by reporting issues or create some pull requests for improvements.

## License
The library is released under the GPL 2.0 or later license.