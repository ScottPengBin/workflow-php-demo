<?php

namespace App\Http\Service\Workflow;

use App\Http\Service\BaseWorkFlowService;
use App\Models\WorkflowNodeSetting;

class ProjectService extends BaseWorkFlowService
{
    /**
     * 创建项目
     */
    public function createProject($data)
    {

        var_dump('createProject');
        var_dump($data);
        return '创建项目成功';
    }

}
