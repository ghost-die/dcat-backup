<?php

namespace Ghost\Backup\Tools;


use Dcat\Admin\Grid\Tools\AbstractTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BackupRun extends AbstractTool
{
    protected $style = 'btn btn-success';

    public function title()
    {
        return '<i class="feather icon-database"></i><span class="d-none d-sm-inline">&nbsp;Backup</span>';
    }

    public function handle(Request $request)
    {

        try {
            ini_set('max_execution_time', 300);

            // start the backup process
            Artisan::call('backup:run');


            $output = Artisan::output();

            return $this->response()->success('操作成功')->refresh();

        } catch (\Exception $e) {
            return $this->response()->error('操作失败')->refresh();

        }

    }
}
