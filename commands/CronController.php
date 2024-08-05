<?php

namespace app\commands;

use app\controllers\LogController;
use yii\console\Controller;

class CronController extends Controller {

    /**
     * This command creates a db backup and send it to administrator.
     */
    public function actionBackup() {
        LogController::log('Backup update called');
        \app\components\Backup::createAndSend();
    }
}
