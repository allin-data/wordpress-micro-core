<?php
/**
 * A task manager that locks and processes the task queue
 */

if (!defined('ABSPATH')) die('Access denied.');

if (!class_exists('Updraft_Task_Manager_1_0')) :

class Updraft_Task_Manager_1_0 {

	protected $loggers;

	public $commands;

	private $semaphore;

	protected static $_instance = null;

	/**
	 * The Task Manager constructor
	 */
	public function __construct() {

		add_action('plugins_loaded', array($this, 'plugins_loaded'));

		if (!class_exists('Updraft_Task_1_0')) require_once('class-updraft-task.php');
		if (!class_exists('Updraft_Task_Manager_Commands_1_0')) require_once('class-updraft-task-manager-commands.php');
		if (!class_exists('Updraft_Semaphore_2_0')) require_once(dirname(__FILE__).'/../updraft-semaphore/class-updraft-semaphore.php');

		$this->commands = new Updraft_Task_Manager_Commands_1_0($this);

		add_action('wp_ajax_updraft_taskmanager_ajax', array($this, 'updraft_taskmanager_ajax'));

		do_action('updraft_task_manager_loaded', $this);
	}

	/**
	 * Loads needed dependencies and sets everything up.
	 */
	public function plugins_loaded() {
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	/**
	 * The Task Manager AJAX handler
	 */
	public function updraft_taskmanager_ajax() {

		$nonce = empty($_REQUEST['nonce']) ? '' : $_REQUEST['nonce'];

		if (!wp_verify_nonce($nonce, 'updraft-task-manager-ajax-nonce') || empty($_REQUEST['subaction']))
			die('Security check failed');

		$subaction = $_REQUEST['subaction'];

		$allowed_commands = Updraft_Task_Manager_Commands_1_0::get_allowed_ajax_commands();
		
		if (in_array($subaction, $allowed_commands)) {

			if (isset($_REQUEST['action_data']))
				$data = $_REQUEST['action_data'];

			$results = call_user_func(array($this->commands, $subaction), $data);
			
			if (is_wp_error($results)) {
				$results = array(
					'result' => false,
					'error_code' => $results->get_error_code(),
					'error_message' => $results->get_error_message(),
					'error_data' => $results->get_error_data(),
				);
			}
			
			echo json_encode($results);
		} else {
			echo json_encode("{'error' : 'No such command found'}");
		}
		die;
	}

	/**
	 * Process a single task in the queue
	 *
	 * @param int|Updraft_Task - $task Task ID or Updraft_Task object.
	 * @return boolean|WP_Error - status of task or error if task not found
	 */
	public function process_task($task) {

		if (!is_a($task, 'Updraft_Task_1_0')) {
			$task_id = (int) $task;
			$task = $this->get_task_instance($task_id);
		}
		
		if (!$task) return new WP_Error('id_invalid', 'Task not found or ID is invalid');

		return $task->attempt();
	}

	/**
	 * Gets a list of all tasks that matches the $status flag
	 *
	 * @param int|Updraft_Task - $task Task ID or Updraft_Task object.
	 * @return String|WP_Error - status of task or error if task not found.
	 */
	public function get_task_status($task) {

		if (!($task instanceof Updraft_Task_1_0)) {
			$task_id = (int) $task;
			$task = $this->get_task_instance($task_id);
		}
		
		if (!$task) return new WP_Error('id_invalid', 'Task not found or ID is invalid');

		return $task->get_status();
	}

	/**
	 * Ends a given task
	 *
	 * @param int|Updraft_Task - $task Task ID or Updraft_Task object.
	 * @return boolean|WP_Error - Status of the operation or error if task not found.
	 */
	public function end_task($task) {
		
		if (!($task instanceof Updraft_Task_1_0)) {
			$task_id = (int) $task;
			$task = $this->get_task_instance($task_id);
		}
		
		if (!$task) return new WP_Error('id_invalid', 'Task not found or ID is invalid');

		return $task->complete();
	}

	/**
	 * Process a the queue of a specifed task type
	 *
	 * @param string $type queue type to process
	 */
	public function process_queue($type) {

		$task_list = $this->get_active_tasks($type);
		$total = count($task_list);

		if (1 > $total) {
			$this->log(sprintf('The queue for tasks of type "%s" is empty. Aborting!', $type));
			return true;
		} else {
			$this->log(sprintf('A total of %d tasks of type %s found and will be processed in this iteration', $total, $type));
		}

		$this->semaphore = new Updraft_Semaphore_2_0($type);

		$last_scheduled_action_called_at = get_option("updraft_last_scheduled_$type");
		$seconds_ago = time() - $last_scheduled_action_called_at;

		$time_out = defined('UPDRAFT_TASK_MANAGER_LOCK_TIMEOUT') ? UPDRAFT_TASK_MANAGER_LOCK_TIMEOUT : 60;

		if ($last_scheduled_action_called_at && $seconds_ago < $time_out) {

			$this->log(sprintf('Failed to gain semaphore lock (%s) - another process which was started only %s seconds_ago is already processing the queue - aborting (if this is wrong - i.e. if the other backup crashed without removing the lock, then another can be started after 1 minute)', $type, $seconds_ago));

			return false;
		}

		update_option("updraft_last_scheduled_$type", time());
		
		if (!$this->semaphore->lock()) {

			$this->log(sprintf('Failed to gain semaphore lock (%s) - another process is already processing the queue - aborting (if this is wrong - i.e. if the other process crashed without removing the lock, then another can be started after 1 minute', $type));

			return false;
		}

		foreach ($task_list as $task) {
			$complete = $this->process_task($task);
		}

		$this->semaphore->unlock();
		$this->log(sprintf('Successfully processed the queue (%s). A total of %d tasks were processed.', $type, $total));
		$this->semaphore->clean_up();

		return true;
	}

	

	/**
	 * Cleans out all complete tasks from the DB.
	 *
	 * @param String $type type of the task
	 */
	public function clean_up_old_tasks($type) {
		$completed_tasks = $this->get_completed_tasks($type);

		if (!$completed_tasks) return false;

		$this->log(sprintf('Cleaning up tasks of type (%s). A total of %d tasks will be deleted.', $type, count($completed_tasks)));

		foreach ($completed_tasks as $task) {
			$task->delete_meta();
			$task->delete();
		}

		return true;
	}

	/**
	 * Fetches  a list of all active tasks
	 *
	 * @param String $type type of the task
	 * @return Mixed - array of Task ojects or NULL if none found
	 */
	public function get_active_tasks($type) {
		return $this->get_tasks('active', $type);
	}

	/**
	 * Gets a list of all completed tasks
	 *
	 * @param String $type type of the task
	 * @return Mixed - array of Task ojects or NULL if none found
	 */
	public function get_completed_tasks($type) {
		return $this->get_tasks('complete', $type);
	}

	/**
	 * Gets a list of all tasks that matches the $status flag
	 *
	 * @param String $status - status of tasks to return, defaults to all tasks
	 * @param String $type   - type of task
	 *
	 * @return Mixed - array of Task objects or NULL if none found
	 */
	public function get_tasks($status, $type) {
		global $wpdb;

		$tasks = array();
		
		if (array_key_exists($status, Updraft_Task_1_0::get_allowed_statuses())) {
			$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tm_tasks WHERE status = %s AND type = %s", $status, $type);
		} else {
			$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tm_tasks WHERE type = %s", $type);
		}

		$_tasks = $wpdb->get_results($sql);
		if (!$_tasks) return;


		foreach ($_tasks as $_task) {
			$task = $this->get_task_instance($_task->id);
			if ($task) array_push($tasks, $task);
		}

		return $tasks;
	}

	public function enqueue_scripts() {

		// Stub to allow for any JS to be added in at a later version
	}

	/**
	 * Retrieve the task instance using its ID
	 *
	 * @access public
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param int $task_id Task ID.
	 * @return Task|Boolean Task object, false otherwise.
	 */
	public function get_task_instance($task_id) {
		global $wpdb;

		$task_id = (int) $task_id;
		if (!$task_id) return false;


		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tm_tasks WHERE id = %d LIMIT 1", $task_id);
		$_task = $wpdb->get_row($sql);

		if (!$_task)
			return false;

		$class_identifier = $_task->class_identifier;

		if (class_exists($class_identifier))
			return new $class_identifier($_task);

		return false;
	}

	/**
	 * Sets the logger for this instance.
	 *
	 * @param array $loggers - the loggers for this task
	 */
	public function set_loggers($loggers) {
		foreach ($loggers as $logger) {
			$this->add_logger($logger);
		}
	}

	/**
	 * Add a logger to loggers list
	 *
	 * @param Object $logger - a logger for the instance
	 */
	public function add_logger($logger) {
		$this->loggers[] = $logger;
	}

	/**
	 * Return list of loggers
	 *
	 * @return array
	 */
	public function get_loggers() {
		return $this->loggers;
	}

	/**
	 * Captures and logs any interesting messages
	 *
	 * @param String $message    - the error message
	 * @param String $error_type - the error type
	 */
	public function log($message, $error_type = 'info') {

		if (isset($this->loggers)) {
			foreach ($this->loggers as $logger) {
				$logger->log($message, $error_type);
			}
		} else {
			error_log($message);
		}
	}

	/**
	 * Returns the only instance of this class
	 *
	 * @return Updraft_Task_Manager_1_0
	 */
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

/**
 * Returns the singleton Updraft_Task_Manager_1_0 class
 */
function Updraft_Task_Manager_1_0() {
	return Updraft_Task_Manager_1_0::instance();
}

$GLOBALS['updraft_task_manager'] = Updraft_Task_Manager_1_0();

endif;
