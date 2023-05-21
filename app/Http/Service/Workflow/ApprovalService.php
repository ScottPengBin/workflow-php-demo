<?php

namespace App\Http\Service\Workflow;

use App\Models\WorkflowNodeSetting;

/**
 * 审批类
 */
class ApprovalService
{
    /**
     * 创建审批
     */
    public function createApproval($data)
    {
        dump('创建审批');
        dump($data);

    }

    /**
     * 同意审批
     */
    public function agreeApproval($data)
    {
        dump('同意审批');
        dump($data);
    }

    /**
     * 拒绝审批
     */
    public function rejectApproval($data)
    {
        dump('拒绝审批');
        dump($data);
    }
}
