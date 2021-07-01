<?php

namespace Ghost\Backup\Repositories;

use Dcat\Admin\Grid;
use Dcat\Admin\Repositories\Repository;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\Artisan;
use Spatie\Backup\BackupDestination\BackupDestination;
use Spatie\Backup\Commands\ListCommand;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatus;
use Spatie\Backup\Tasks\Monitor\BackupDestinationStatusFactory;

class Backup extends Repository
{

    public function getPrimaryKeyColumn()
    {
        return 'Disk';
    }

    public function get(Grid\Model $model)
    {


        $statuses = BackupDestinationStatusFactory::createForMonitorConfig(config('backup.monitor_backups'));

        $listCommand = new ListCommand();

        $rows = $statuses->map(function (BackupDestinationStatus $backupDestinationStatus) use ($listCommand) {
            return $listCommand->convertToRow($backupDestinationStatus);
        })->all();


        $data = [];
        foreach ($statuses as $index => $status) {
            $name = $status->backupDestination()->backupName();

            $files = array_map('basename', $status->backupDestination()->disk()->allFiles($name));


            $rows[$index]['files'] = array_slice(array_reverse($files), 0, 30);

            $headers = ['Name', 'Disk', 'Reachable', 'Healthy', 'OfBackups', 'NewestBackup', 'UsedStorage','Files'];

            $data[$index] = array_combine($headers,$rows[$index]);
        }

        return $data;
    }
}
