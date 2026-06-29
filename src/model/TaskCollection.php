<?php //src/model/TaskCollection.php
namespace pkpudev\gantt\model;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use yii\base\BaseObject;

/**
 * Collection for Gantt task
 * 
 * Here is long desc
 *
 * @author Zein Miftah <zeinmiftah@gmail.com>
 * @since 1.0
 */
class TaskCollection extends BaseObject implements IteratorAggregate, Countable
{
    /**
     * @var array<int, Task> $tasks
     */
    protected array $tasks = [];

    /**
     * @var array $data
     */
    protected array $data = [];

    public function addTask(Task $task)
    {
        array_push($this->tasks, $task);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->tasks);
    }

    public function count()
    {
        return count($this->tasks);
    }

    public function exists()
    {
        return $this->count() > 0;
    }
}