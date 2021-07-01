<?php

namespace Ghost\Backup\Http\Controllers;

use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Table;
use Ghost\Backup\Repositories\Backup;
use Ghost\Backup\Tools\BackupRun;
use Ghost\Backup\Tools\BatchDelete;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class DcatBackupController extends AdminController
{
    protected function grid()
    {

        return Grid::make(new Backup(), function (Grid $grid) {

            $grid->column('Name');
            $grid->column('Disk');

            $grid->column('Reachable');
            $grid->column('Healthy');
            $grid->column('OfBackups');
            $grid->column('NewestBackup');
            $grid->column('UsedStorage');

            $grid->column('Files')->display('') // 设置按钮名称
            ->modal(function ($modal) {
                // 设置弹窗标题
                $modal->title('Files');
                // 自定义图标
                $modal->icon('feather icon-eye');
                $header = [
                    'Files',
                    'Actions'
                ];
                $data = collect($this['Files'])->map(function ($value){
                    $url = admin_url('backup/download?disk='.$this['Disk'].'&file='.$this['Name'].'/'.$value);
                    $delete_url = admin_url('backup/delete?disk='.$this['Disk'].'&file='.$this['Name'].'/'.$value);
                    $action = <<<HTML
<a target="_blank" class="btn btn-sm btn-outline-info text-info" href="$url"><i class="feather icon-download"></i></a>&nbsp;
<a class="delete  btn btn-sm btn-outline-danger text-danger" data-url="$delete_url"><i class="feather icon-trash"></i></a>
<script>
$('.delete').off('click').on('click', function (e) {
    let url = $(this).data('url');
    Dcat.confirm('确认要删除这行数据吗？', null, function () {
        $.ajax(
                {
                    url: url,
                    dataType: 'json',
                    type:"post",
                    delay: 250,
                    data: {_method:'delete'},
                    success: function (response) {
                        Dcat.handleJsonResponse(response);
                        return false;
                    },
                }
            );
        });
    });
</script>
HTML;
                    $res['file'] = $value;
                    $res['action'] = $action;
                    return $res;
                });
                return new Table($header,$data);
            });

            $grid->tools(new BackupRun);

            $grid->disableBatchDelete();
            $grid->disableCreateButton();
            $grid->disablePagination();
            $grid->disableActions();
            $grid->batchActions([
                new BatchDelete('删除')
            ]);
        });
    }

    public function download(Request $request)
    {
        $disk = $request->get('disk');
        $file = $request->get('file');


        $storage = Storage::disk($disk);

        $fullPath = $storage->getDriver()->getAdapter()->applyPathPrefix($file);

        if (File::isFile($fullPath)) {
            return response()->download($fullPath);
        }

        return response('', 404);
    }


    public function delete(Request $request)
    {
        $disk = Storage::disk($request->get('disk'));
        $file = $request->get('file');

        if ($disk->exists($file)) {
            $disk->delete($file);

            return response()->json([
                'status'  => true,
                'data' => [
                    "then"=>[
                        "action"=>"refresh",
                        "value"=>false
                    ],
                    "type"=> "success",
                    'message'=>'操作成功'
                ]
            ]);
        }

        return response()->json([
            'status'  => false,
            'data' => [
                "then"=>[
                    "action"=>"refresh",
                    "value"=>false
                ],
                "type"=> "error",
                'message'=>'操作失败'
            ]
        ]);
    }
}
