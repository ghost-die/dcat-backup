<?php


namespace Ghost\Backup\Tools;


use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BatchDelete extends BatchAction
{


    public function confirm()
    {
        return '您确定删除备份吗?';
    }

    public function handle(Request $request)
    {


        $disks = $this->getKey();


        $name = config('backup.backup.name');

        foreach ($disks as $disk)
        {
            $disk = Storage::disk($disk);
            if ($disk->exists($name)) {
                $disk->deleteDirectory($name);
            }
        }

        return $this->response()->success('操作成功')->refresh();
    }


}
