<?php

namespace App\Workflow;

use App\Models\WorkflowNodeSetting;
use Illuminate\Database\Eloquent\Collection;

class BaseEngineWorkflow
{
    public function workflowStartCondition($trigger)
    {
        //todo 判断是否满足触发条件
        return true;
    }


    public function getNextNode(Collection $nodes)
    {
        //找出最符合条件的node节点
        $node = $nodes->first();

        $nextNode = WorkflowNodeSetting::query()->where('id',$node->next_node_id)->first();

        //node type ....
        //todo

        return $nextNode;
    }

}
