<?php

// Augmenter la limite de temps d'exécution
ini_set('max_execution_time', '600');
ini_set('memory_limit', '-1');

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
