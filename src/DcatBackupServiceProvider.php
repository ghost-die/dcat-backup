<?php

namespace Ghost\Backup;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Admin;
use Illuminate\Support\Carbon;

class DcatBackupServiceProvider extends ServiceProvider
{
	protected $js = [
        'js/index.js',
    ];
	protected $css = [
		'css/index.css',
	];

    // 定义菜单
    protected $menu = [
        [
            'title' => 'Backup',
            'uri' => 'backup',
            'icon' => 'fa-toggle-off', // 图标可以留空
        ],
    ];


    public function register()
	{
	}

	public function init()
	{
		parent::init();
	}
}
