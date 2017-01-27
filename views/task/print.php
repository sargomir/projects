<?php
use app\modules\projects\Projects as Module;

echo "<div class=\"project-form\"><table>";
foreach ( $model->attributes as $attr_name => $attr_value ) {
	if (isset ( $model->attributeLabels () [$attr_name] )) {
		$label = $model->attributeLabels () [$attr_name];
		$value = $attr_value;
		$old_value = isset ( $changedAttributes [$attr_name] ) ? $changedAttributes [$attr_name] : $value;
		
		switch ($attr_name) {
			case 'project_id' :
				$value = $model->project->project;
				$project = app\modules\projects\models\Project::findOne ( $old_value );
				$old_value = isset ( $project->project ) ? $project->project : $value;
				break;
			case 'project_part_id' :
				$value = $model->project_part->part;
				$part = app\modules\projects\models\ProjectPart::findOne ( $old_value );
				$old_value = isset ( $part->part ) ? $part->part : $value;
				break;
			case 'worker_id' :
				$value = $model->worker->username;
				$user = app\modules\projects\models\AuthProfile::findOne ( $old_value );
				$old_value = isset ( $user->username ) ? $user->username : $value;
				break;
			case 'technical_lead_id' :
				$value = $model->technical_lead->username;
				$user = app\modules\projects\models\AuthProfile::findOne ( $old_value );
				$old_value = isset ( $user->username ) ? $user->username : $value;
				break;
			case 'project_lead_id' :
				$value = $model->project_lead->username;
				$user = app\modules\projects\models\AuthProfile::findOne ( $old_value );
				$old_value = isset ( $user->username ) ? $user->username : $value;
				break;
			case 'project_manager_id' :
				$value = $model->project_manager->username;
				$user = app\modules\projects\models\AuthProfile::findOne ( $old_value );
				$old_value = isset ( $user->username ) ? $user->username : $value;
				break;
		}
		if ($value != $old_value)
			$value = "<ins>{$value}</ins><br/><del>{$old_value}</del>";
		echo "<tr><th>{$label}</th><td>{$value}</td>";
	}
}
;
echo "</table></div>";

?>

<style type="text/css">
th {
	text-align: left;
	vertical-align: sub;
}
</style>