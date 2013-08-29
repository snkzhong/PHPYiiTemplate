<?php

define('XHPROF_ENABLE', true);

define('Environment', 'development');
//define('Environment', 'production');

$xhprofLogInterval = ( (rand(1, 5) == 3) ? true : false );

if (XHPROF_ENABLE && function_exists('xhprof_enable')) {
	if (Environment!='production' || (Environment=='production' && $xhprofLogInterval)) {
		xhprof_enable();
	}
}

define('BASE_DIR', __DIR__);
define('CONFIG_DIR', BASE_DIR.'/protected/config/');
define('IOLogDir', BASE_DIR.'/protected/logs/');
define('VENDORDIR', BASE_DIR.'/protected/vendor/');
define('TMPDIR', BASE_DIR.'/protected/tmp/');

if (Environment == 'development' || Environment == 'test') {
	error_reporting(E_ALL ^ E_NOTICE);

	defined('YII_DEBUG') or define('YII_DEBUG',true);
	defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);	
}
elseif (Environment == 'production') {
	error_reporting(0);
}

ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.CONFIG_DIR);

$yii = BASE_DIR.'/protected/vendor/yii/framework/yii.php';
require_once($yii);

$app = Yii::createWebApplication( CONFIG_DIR.'main.php' );
Yii::beginProfile('requestLifeCycle');
$app->run();


if (XHPROF_ENABLE && function_exists('xhprof_enable')) {
	if (Environment!='production' || (Environment=='production' && $xhprofLogInterval)) {
		$xhprof_data = xhprof_disable();
		$XHPROF_ROOT = __DIR__.'/../../xhprof';
		include $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
		include $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

		$xhprof_runs = new XHProfRuns_Default();

		$id = Registry::instance()->router;
		$id = $id ? $id : 'xhprof_log';
		$id = str_replace('/', '_', $id);
		$run_id = $xhprof_runs->save_run($xhprof_data, $id);
	}
}