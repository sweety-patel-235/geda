<?php
	$this->Html->addCrumb('Projects', ['controller' => 'projects']); 
    $this->Html->addCrumb($project->name); 
?>
<div class="container">
<p>Location: <?= h($project->address) ?></p>
<p>Capacity: <?= h($project->capacity_kw) ?></p>
<p>Contact No: <?= h($project->customer->mobile) ?></p>
<p>Email: <?= h($project->customer->email) ?></p>
<p>Date: <?= ($project->created !== null) ? $project->created->format(LIST_DATE_FORMAT) : "" ?></p>
</div>