<?php

namespace App\Http\Service\Workflow;

use App\Http\Service\BaseWorkFlowService;

class ProjectService extends BaseWorkFlowService
{
    public function createProject($data)
    {

        var_dump('createProject');
        var_dump($data);
        return '创建项目成功';
    }
}
