<?php

namespace tpext\builder\admin\controller;

use think\Controller;
use tpext\builder\common\model\Attachment as AttachmentModel;
use tpext\builder\traits\actions\HasAutopost;
use tpext\builder\traits\actions\HasBase;
use tpext\builder\traits\actions\HasIndex;
use tpext\builder\common\Module;

/**
 * Undocumented class
 * @title 文件管理
 */
class Attachment extends Controller
{
    use HasBase;
    use HasIndex;
    use HasAutopost;

    protected $dataModel;

    protected function initialize()
    {
        $this->dataModel = new AttachmentModel;

        $this->pageTitle = '文件管理';
        $this->postAllowFields = ['name'];
        $this->pagesize = 8;
    }

    protected function filterWhere()
    {
        $searchData = request()->get();

        $where = [];

        if (request()->module() == 'admin') {

            $admin = session('admin_user');

            if ($admin['role_id'] != 1) {
                $where[] = ['admin_id', 'eq', $admin['id']];
            }
        }

        if (!empty($searchData['name'])) {
            $where[] = ['name', 'like', '%' . $searchData['name'] . '%'];
        }

        if (!empty($searchData['url'])) {
            $where[] = ['url', 'like', '%' . $searchData['url'] . '%'];
        }

        $ext = input('ext');

        if ($ext) {
            $where[] = ['suffix', 'in', explode(',', $ext)];
        }

        if (!empty($searchData['suffix'])) {
            $where[] = ['suffix', 'in', $searchData['suffix']];
        }

        return $where;
    }

    /**
     * 构建搜索
     *
     * @return void
     */
    protected function buildSearch()
    {
        $search = $this->search;

        $search->text('name', '文件名', '6 col-xs-6')->size('4 col-xs-4', '8 col-xs-8')->maxlength(55);
        $search->text('url', 'url链接', '6 col-xs-6')->size('4 col-xs-4', '8 col-xs-8')->maxlength(200);

        $exts = [];
        $arr = [];

        $ext = input('get.ext');
        if ($ext) {
            $arr =  explode(',', $ext);
        } else {
            $config = Module::getInstance()->getConfig();
            $arr =  explode(',', $config['allow_suffix']);
        }

        foreach ($arr as $a) {
            $exts[$a] = $a;
        }

        $search->multipleSelect('suffix', '后缀', '6 col-xs-6')->size('4 col-xs-4', '8 col-xs-8')->options($exts);
    }
    /**
     * 构建表格
     *
     * @return void
     */
    protected function buildTable(&$data = [])
    {
        $table = $this->table;

        $choose = input('get.choose', 0);

        $table->show('id', 'ID');
        $table->text('name', '文件名')->autoPost();
        $table->file('file', '文件')->thumbSize(50, 50);
        if (!$choose) {
            $table->show('mime', 'mime类型');
            $table->show('size', '大小')->to('{val}MB');
            $table->show('suffix', '后缀')->getWrapper()->addStyle('width:80px');
            $table->show('storage', '位置');
        }

        $table->raw('url', '链接')->to('<a href="{val}" target="_blank">{val}</a>');
        $table->show('create_time', '添加时间')->getWrapper()->addStyle('width:160px');

        $table->getToolbar()
            ->btnRefresh()
            ->btnImport(url('uploadSuccess'), '', ['250px', '205px'], 0, '上传', 'btn-pink', 'mdi-cloud-upload', 'title="上传文件"', '')
            ->btnToggleSearch();

        foreach ($data as &$d) {
            $d['file'] = $d['url'];
        }

        unset($d);

        $table->useCheckbox(false);

        if ($choose) {
            $table->getActionbar()->btnPostRowid('choose', url('choose', ['id' => input('id'), 'limit' => input('limit')]), '选择', 'btn-success', 'mdi-note-plus-outline', '', false);
        } else {
            $table->useActionbar(false);
        }
    }

    /**
     * Undocumented function
     *
     * @title 选中文件
     * @return mixed
     */
    public function choose($id, $limit)
    {
        $ids = input('post.ids/d', '0');
        if (empty($ids)) {
            $this->error('参数有误');
        }

        $file = $this->dataModel->get($ids);

        if ($file) {
            $script = '';

            if ($limit < 2) {
                $script = "<script>parent.$('#{$id}').val('{$file['url']}').trigger('change');parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";
            } else {
                $script = "<script>parent.$('#{$id}').val(parent.$('#{$id}').val()+(parent.$('#{$id}').val()?',':'')+'{$file['url']}').trigger('change');parent.layer.close(parent.layer.getFrameIndex(window.name));</script>";
            }

            return json(['code' => 1, 'script' => $script]);
        } else {
            $this->error('文件不存在');
        }
    }

    /**
     * Undocumented function
     *
     * @title 上传成功
     * @return mixed
     */
    public function uploadSuccess()
    {
        $builder = $this->builder('上传成功');

        $builder->addScript('parent.lightyear.notify("上传成功","success");parent.$(".search-refresh").trigger("click");parent.layer.close(parent.layer.getFrameIndex(window.name));'); //刷新列表页

        return $builder->render();
    }
}
