<?php
/**
 * The base class which must be extended to use the tasks library
 */

if (!defined('ABSPATH')) die('Access denied.');

if (!defined('UPDRAFT_TASKS_LOCK_TIMEOUT')) define('UPDRAFT_TASKS_LOCK_TIMEOUT', 60);

if (!class_exists('Updraft_Task_1_0')) :

if (!class_exists('Updraft_Task_Options')) require_once('class-updraft-task-options.php');
if (!class_exists('Updraft_Task_Meta')) require_once('class-updraft-task-meta.php');
if (!class_exists('Updraft_Semaphore_2_0')) require_once(dirname(__FILE__).'/../updraft-semaphore/class-updraft-semaphore.php');

abstract class Updraft_Task_1_0 {

	/**
	 * A unique ID for the specific task
	 *
	 * @var int
	 */
	private $id;

	/**
	 * The user id of the creator of this task
	 *
	 * @var string
	 */
	private $user_id;

	/**
	 * A text description for the task
	 *
	 * @var string
	 */
	private $description;

	/**
	 * A type for the task
	 *
	 * @var string
	 */
	private $type;

	/**
	 * A timestamp indicating the time the task was created
	 *
	 * @var string
	 */
	private $time_created;

	/**
	 * The number of times this task was attempted
	 *
	 * @var int
	 */
	private $attempts;

	/**
	 * A text description describing the status of the task
	 *
	 * @var string
	 */
	private $status;

	/**
	 * An identifier indicating which child class created this instance
	 *
	 * @var string
	 */
	private $class_identifier;

	/**
	 * An semaphore instance to prevent multiple processes running this at once.
	 *
	 * @var Object
	 */
	private $semaphore;

	/**
	 * A logger object that can be used to capture interesting events / messages
	 *
	 * @var Object
	 */
	protected $_loggers;


	/**
	 * The Task constructor
	 *
	 * @param UpdraftPlus_Task|object $task UpdraftPlus_Task object.
	 */
	public function __construct($task) {
		foreach (get_object_vars($task) as $key => $value)
			$this->$key = $value;
	}


	/**
	 * Sets the instance ID.
	 *
	 * @param String $instance_id - the instance ID
	 */
	public function set_id($instance_id) {
		$this->id = $instance_id;
	}

	/**
	 * Gets the instance ID.
	 *
	 * @return String the instance ID
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Sets the description.
	 *
	 * @param String $description - the description of the task
	 */
	public function set_description($description) {
		$this->description = $description;
	}

	/**
	 * Gets the task description
	 *
	 * @return String $description - the description of the task
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Sets the type.
	 *
	 * @param String $type - the type of the task
	 */
	public function set_type($type) {
		$this->type = $type;
	}

	/**
	 * Gets the number of times this task was attempted
	 *
	 * @return int $attempts - the count
	 */
	public function get_attempts() {
		return $this->attempts;
	}

	/**
	 * Sets the number of times this task was attempted
	 *
	 * @param String $attempts - the count
	 */
	public function set_attempts($attempts) {
		if (is_numeric($attempts))
			$this->attempts = $attempts;
		else return false;

		return $this->update_attempts($this->id, $this->attempts);
	}

	/**
	 * Gets the task type
	 *
	 * @return String $type - the type of the task
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Sets the task status.
	 *
	 * @param String $status - the status of the task
	 *
	 * @return Boolean - the result of the status update
	 */
	public function set_status($status) {
		
		if (array_key_exists($status, self::get_allowed_statuses()))
			$this->status = $status;
		else return false;

		return $this->update_status($this->id, $this->status);
	}

	/**
	 * Gets the task status
	 *
	 * @return String $status - the status of the task
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Sets the logger for this task.
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
	 * @param Object $logger - a logger for the task
	 */
	public function add_logger($logger) {
		$this->_loggers[] = $logger;
	}

	/**
	 * Return list of loggers
	 *
	 * @return array
	 */
	public function get_loggers() {
		return $this->_loggers;
	}


	/**
	 * The initialisation function that accepts and processes any parameters needed before the task starts
	 *
	 * @param Array $options - array of options
	 *
	 * @uses update_option
	 */
	public function initialise($options = array()) {

		do_action('ud_task_before_initialise', $this, $options);

		/**
		 * Parse incoming $options into an array and merge it with defaults
		 */
		$defaults = $this->get_default_options();
		$options = wp_parse_args($options, $defaults);

		foreach ($options as $option => $value) {
			$this->update_option($option, $value);
		}

		do_action('ud_task_initialise_complete', $this, $options);

	}

	/**
	 * Attempts to perform the task
	 *
	 * @return boolean Status of the attempt
	 */
	public function attempt() {

		if ($this->get_status() == 'complete') {
			$this->log("Attempting already complete task with ID : {$this->get_id()}, and type '{$this->get_type()}'. Aborting !");
			return true;
		}

		$attempts = $this->get_attempts();

		if ($attempts >= self::get_max_attempts()) {
			$this->fail("max_attempts_exceeded", "Maximum attempts ($attempts) exceeded for task");
			return false;
		}

		$label = $this->get_unique_label();
		$this->semaphore = new Updraft_Semaphore_2_0($label);

		$last_scheduled_action_called_at = get_option("updraft_last_scheduled_$label");
		$seconds_ago = time() - $last_scheduled_action_called_at;

		$time_out = defined('UPDRAFT_TASKS_LOCK_TIMEOUT') ? UPDRAFT_TASKS_LOCK_TIMEOUT : 60;

		if ($last_scheduled_action_called_at && $seconds_ago < $time_out) {

			$this->log(sprintf('Failed to gain semaphore lock (%s) - another process which was started only %s seconds_ago is already processing the task - Aborting!', $label, $seconds_ago));
			return false;
		}

		update_option("updraft_last_scheduled_$label", time());
		
		if (!$this->semaphore->lock()) {
			$this->log(sprintf('Failed to gain semaphore lock (%s) - another process is already processing the task - Aborting!', $label));
			return false;
		}

		$this->log("Processing task with ID : {$this->get_id()}, and type '{$this->get_type()}'");
		$this->set_attempts(++$attempts);
		$status = $this->run();
		
		if ($status) {
			$this->complete();
			$this->semaphore->unlock();
			$this->log("Completed processing task with ID : {$this->get_id()}, and type '{$this->get_type()}'");
			$this->semaphore->clean_up();
		}

		return $status;
	}

	/**
	 * This function is called to allow for the task to perfrom a small chunk of work.
	 * It should be written in a way that anticipates it being killed off at any time.
	 */
	abstract public function run();

	/**
	 * Any clean up code goes here.
	 */
	public function complete() {

		do_action('ud_task_before_complete', $this);

		$this->set_status('complete');

		do_action('ud_task_completed', $this);

		return true;
	}

	/**
	 * Fires if the task fails, any clean up code and logging should go here
	 *
	 * @param String $error_code    - A code for the failure
	 * @param String $error_message - A description for the failure
	 */
	public function fail($error_code = "Unknown", $error_message = "Unknown") {

		do_action('ud_task_before_failed', $this);

		$this->set_status('failed');
		$this->log(sprintf("Task with ID %d and type (%s) failed with error code %s - %s", $this->id, $this->type, $error_code, $error_message));

		$this->update_option("error_code", $error_code);
		$this->update_option("error_message", $error_message);

		if ($this->semaphore) $this->semaphore->clean_up();

		do_action('ud_task_failed', $this);

		return true;
	}

	/**
	 * Prints any information about the task that the UI can use on the front end for debugging
	 * @param  String $title  the header to use in the report
	 *
	 * @return String  The task report HTML
	 */
	public function print_task_report_widget($title = 'Task Summary') {

		$ret = "";

		$status = $this->get_status();
		$stage = $this->get_option('stage') ? $this->get_option('stage') : 'Unknown';
		$description = $this->get_status_description($status);


		$ret .= "<div class='task task-report task-{$this->type}' id='task-id-{$this->id}'>";
		$ret .= "<h4>Task Summary</h4>";
		$ret .= "<ul class='properties-list task-{$this->type}'>";
		
		foreach ($this as $key => $value) {
			$ret .= sprintf("<li> %s : %s </li>", $key, $value);
		}
		$ret .='</ul>';

		$ret .= "<h4> $title </h4>";
		$ret .= "<ul class='data-list task-{$this->type}'>";

		foreach ($this->get_all_options() as $key => $value) {
			if (is_array(maybe_unserialize($value))) {
				$ret .= sprintf("<li> %s</li>", $key);
				$ret .= "<ul class='sub-list'>";
				foreach (maybe_unserialize($value) as $k => $v) {
					$ret .= sprintf("<li> %s => %s </li>", $k, $v);
				}
				$ret .= "</ul>";
			} else {
				$ret .= sprintf("<li> %s : %s </li>", $key, $value);
			}
		}
		$ret .='</ul>';
		$ret .='</div>';

		return apply_filters('ud_print_task_report_widget', $ret, $this);

	}

	/**
	 * This method gets an option from the task options in the WordPress database if available,
	 * otherwise returns the default for this task type
	 *
	 * @param  String $option  the name of the option to get
	 * @param  Mixed  $default a value to return if the option is not currently set
	 *
	 * @return Mixed  The option from the database
	 */
	public function get_option($option = null, $default = null) {
		return Updraft_Task_Options::get_task_option($this->id, $option, $default);
	}

	/**
	 * This method is used to add a task option stored in the WordPress database
	 *
	 * @param  String $option the name of the option to update
	 * @param  Mixed  $value  the value to save to the option
	 *
	 * @return Mixed          the status of the add operation
	 */
	public function add_option($option, $value) {
		return Updraft_Task_Options::update_task_option($this->id, $option, $value);
	}

	/**
	 * This method is used to update a task option stored in the WordPress database
	 *
	 * @param  String $option the name of the option to update
	 * @param  Mixed  $value  the value to save to the option
	 *
	 * @return Mixed          the status of the update operation
	 */
	public function update_option($option, $value) {
		return Updraft_Task_Options::update_task_option($this->id, $option, $value);
	}

	/**
	 * This method is used to delete a task option stored in the WordPress database
	 *
	 * @param  String $option the option to delete
	 *
	 * @return Boolean        the result of the delete operation
	 */
	public function delete_option($option) {
		return Updraft_Task_Options::delete_task_option($this->id, $option);
	}

	/**
	 * This method gets all options assoicated with a task
	 */
	public function get_all_options() {
		return Updraft_Task_Options::get_all_task_options($this->id);
	}

	/**
	 * Retrieve default options for this task.
	 * This method should normally be over-ridden by the child.
	 *
	 * @return Array - an array of options
	 */
	public function get_default_options() {

		$this->log(sprintf('The get_default_options() method was not over-ridden for the class : %s', $this->get_description()));

		return array();
	}

	/**
	 * Returns a unique label for this instance that can be used as an identifier
	 *
	 * @return String - a unique label for this instance
	 */
	protected function get_unique_label() {
		return apply_filters('ud_task_unique_label', $this->id."-".$this->type, $this);
	}

	/**
	 * Updates the status of the given task in the DB
	 *
	 * @param String $id     - the id of the task
	 * @param String $status - the status of the task
	 *
	 * @return Boolean - the stauts of the update operation
	 */
	public function update_status($id, $status) {

		if (!array_key_exists($status, self::get_allowed_statuses()))
			return false;

		global $wpdb;
		$sql = $wpdb->prepare("UPDATE {$wpdb->prefix}tm_tasks SET status = %s WHERE id = %d", $status, $id);

		return $wpdb->query($sql);
	}

	/**
	 * Updates the number of attempts made for the given task in the DB
	 *
	 * @param String $id       - the id of the task
	 * @param int 	 $attempts - the status of the task
	 *
	 * @return Boolean - the stauts of the update operation
	 */
	public function update_attempts($id, $attempts) {

		if (!is_numeric($attempts))
			return false;

		global $wpdb;
		$sql = $wpdb->prepare("UPDATE {$wpdb->prefix}tm_tasks SET attempts = %s WHERE id = %d", $attempts, $id);

		return $wpdb->query($sql);
	}

	/**
	 * Cleans out the given task from the DB
	 *
	 * @return Boolean - the status of the delete operation
	 */
	public function delete() {
		global $wpdb;

		$sql = $wpdb->prepare("DELETE FROM {$wpdb->prefix}tm_tasks WHERE id = %d", $this->id);
		return $wpdb->query($sql);
	}

	/**
	 * Cleans out the given task meta from the DB
	 *
	 * @return Boolean - the status of the delete operation
	 */
	public function delete_meta() {
		return Updraft_Task_Meta::bulk_delete_task_meta($this->id);
	}

	/**
	 * Helper function to convert object to array.
	 *
	 * @return array Object as array.
	 */
	public function to_array() {
		$task = get_object_vars($this);

		foreach (array( 'task_options', 'task_data', 'task_logs', 'task_extras' ) as $key) {
			if ($this->__isset($key))
				$task[$key] = $this->__get($key);
		}

		return $task;
	}

	/**
	 * Captures and logs any interesting messages
	 *
	 * @param String $message    - the error message
	 * @param String $error_type - the error type
	 */
	public function log($message, $error_type = 'info') {

		if (isset($this->_loggers)) {
			foreach ($this->_loggers as $logger) {
				$logger->log($message, $error_type);
			}
		} else {
			error_log($message);
		}
	}

	/**
	 * Retrieve all the supported task statuses.
	 *
	 * Tasks should have a limited set of valid status values, this method provides a
	 * list of values and descriptions.
	 *
	 * @return array List of task statuses.
	 */
	public static function get_allowed_statuses() {
		$status = array(
			'initialised' => __('Initialised'),
			'active'   => __('Active'),
			'paused' => __('Paused'),
			'complete' => __('Completed'),
			'failed' => __('Failed')
		);

		return apply_filters('ud_allowed_task_statuses', $status);
	}


	/**
	 * Retrieve the max attempts permitted for task type
	 *
	 * @return int Max attempts permitted for task type
	 */
	public static function get_max_attempts(){
		return apply_filters("ud_max_attempts", 5, get_called_class());
	}

	/**
	 * Retrieve the text description of the task status.
	 *
	 * @param String $status - The task status
	 *
	 * @return String 	Description of the task status.
	 */
	public static function get_status_description($status) {
		$list = self::get_allowed_statuses();

		if (!array_key_exists($status, self::get_allowed_statuses()))
			return __('Unknown');

		return apply_filters("ud_task_status_description_{$status}", $list[$status], $status, $list);
	}


	/**
	 * Creates a new task instance and returns it
	 *
	 * @access public
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @param String $type 		  A identifier for the task
	 * @param String $description A description of the task
	 * @param Mixed  $options     A list of options to initialise the task
	 *
	 * @return Updraft_Task|false Task object, false otherwise.
	 */
	public static function create_task($type, $description, $options = array()) {
		global $wpdb;

		$user_id = get_current_user_id();
		$class_identifier = get_called_class();

		if (!$user_id)
			return false;

		$sql = $wpdb->prepare("INSERT INTO {$wpdb->prefix}tm_tasks (type, user_id, description, class_identifier, status) VALUES (%s, %d, %s, %s, %s)", $type, $user_id, $description, $class_identifier, 'active');

		$wpdb->query($sql);

		$task_id = $wpdb->insert_id;

		if (!$task_id)
			return false;

		$_task = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tm_tasks WHERE id = {$task_id} LIMIT 1");

		$task = new $class_identifier($_task);

		if (!$task)
			return false;

		$task->initialise($options);

		return $task;
	}
}
endif;
