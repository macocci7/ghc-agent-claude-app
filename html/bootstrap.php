<?php

// Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load helper functions
require_once __DIR__ . '/helpers.php';

// Load global library classes
require_once __DIR__ . '/libs/Database.php';
require_once __DIR__ . '/libs/Session.php';
require_once __DIR__ . '/libs/Router.php';
require_once __DIR__ . '/libs/View.php';
require_once __DIR__ . '/libs/Csrf.php';

// Import classes to global namespace for backward compatibility
use Libs\Database;
use Libs\Session;
use Libs\Router;
use Libs\View;
use Libs\Csrf;

// Create global aliases
class_alias('Libs\Database', 'Database');
class_alias('Libs\Session', 'Session');
class_alias('Libs\Router', 'Router');
class_alias('Libs\View', 'View');
class_alias('Libs\Csrf', 'Csrf');
