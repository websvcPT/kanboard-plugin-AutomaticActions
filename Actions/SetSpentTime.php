<?php

namespace Kanboard\Plugin\AutomaticActions\Actions;

use Kanboard\Model\TaskModel;
use Kanboard\Action\Base;

/**
 * Set Task Spent Time
 *
 * @package actions
 * @author  Nelson Dias
 */
class SetSpentTime extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Set task Spent Time when the task is moved to another column if Spent Time = 0 (Plugin)');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_MOVE_COLUMN,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'column_id' => t('Column'),
        );
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'task' => array(
                'date_started',
                'time_spent',
            ),
        );
    }

    /**
     * Execute the action
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $time_spent = $this->convertTimeToHours($data); 
        
        $values = array(
            'id' => $data['task_id'],
            'time_spent' => $time_spent,
            'description' => "date_started: " . $data['task']['date_started']. " --- time: ".time()
        );

        return $this->taskModificationModel->update($values, false);
        # return $this->taskModificationModel->update(array('id' => $data['task_id'], 'title' => $this->getParam('title')));
    }

    /**
     * Check if the event data meet the action condition
     *  
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        //&& $data['task']['date_started'] == 0
        return ( $data['task']['column_id'] == $this->getParam('column_id')) ;
    }

    /**
     *  Calculates the diference between start tade and current time
     *  converts seconds into hours and fraction the value
     *  If the value is smaller than 1 minute will hardcode the 0.017 value
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return number
     */
    private function convertTimeToHours(array $data){

        $calc = (time() - $data['task']['date_started']) / 60 / 60 / 10;

        if($calc<0.017){
            $calc = 0.017;
        }

        return $calc;

    }
}
