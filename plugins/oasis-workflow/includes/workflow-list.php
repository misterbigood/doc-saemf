<?php
class FCWorkflowList extends FCWorkflowBase
{

	static function get_table_header()
	{
		echo "<tr>";
		echo "<th scope='col' class='manage-column check-column' ><input type='checkbox'></th>";
		echo "<th>" . __("Title", "oasisworkflow") . "</th>";
		echo "<th>" . __("Version", "oasisworkflow") . "</th>";
		echo "<th>" . __("Start Date", "oasisworkflow") . "</th>";
		echo "<th>" . __("End Date", "oasisworkflow") . "</th>";
		echo "<th>" . __("Post/Pages in workflow", "oasisworkflow") . "</th>";
		echo "<th>" . __("Is Valid?", "oasisworkflow") . "</th>";
		echo "</tr>";
	}

	static function get_workflow_list( $action = null )
	{
		global $wpdb;
		$currenttime = date("Y-m-d") ;
		if( $action == "all" )
			return FCWorkflowList::get_all_workflows();

		if( $action == "active" ) {
			$sql = "SELECT * FROM " . FCUtility::get_workflows_table_name() . " WHERE start_date <= %s AND end_date >= %s AND is_valid = 1" ;
			return $wpdb->get_results( $wpdb->prepare( $sql, array( $currenttime, $currenttime ))) ;
		}

		if( $action == "inactive" ) {
			$sql = "SELECT * FROM " . FCUtility::get_workflows_table_name() . " WHERE NOT(start_date <= %s AND end_date >= %s AND is_valid = 1)" ;
			return $wpdb->get_results( $wpdb->prepare( $sql, array( $currenttime, $currenttime ))) ;
		}
	}

	/**
	 * get workflow count by status (All, Active, Inactive)
	 *
	 * @return mixed, object with all the counts
	 *
	 * @since 1.0
	 */
	static function get_workflow_count()
	{
		global $wpdb;
		$currenttime = date("Y-m-d") ;
		
		// get all the workflows
		// also get all the active workflows ( end date is null OR end date is greater than today AND the workflow is valid)
		$sql = "SELECT
					SUM(ID > 0) as wfall,
					SUM((start_date <= %s AND end_date <> '0000-00-00' AND end_date >= %s AND is_valid = 1) 
						  OR 
						 (start_date <= %s AND end_date = '0000-00-00' AND is_valid = 1)) as wfactive
					FROM " . FCUtility::get_workflows_table_name();
		$wf_count_map = $wpdb->get_row( $wpdb->prepare( $sql, array( $currenttime, $currenttime, $currenttime ))) ;
		
		// find the count of inactive workflows by subtracting active workflows from all workflows.
		$wf_count_map = (array)$wf_count_map;
		$wf_count_map['wfinactive'] = $wf_count_map['wfall'] - $wf_count_map['wfactive'];
		$wf_count_map = (object)$wf_count_map;
		
		return $wf_count_map;
	}
}
?>