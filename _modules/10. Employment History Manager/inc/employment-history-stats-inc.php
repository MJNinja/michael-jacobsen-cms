<?php $totalSection = 4;?>
<div class="module-stats-label">
    Completed Sections:
    <b><?php $totalCompleted = $employmentHistoryManager->getCompletedSections(); echo $totalCompleted;?></b>
</div>
<div class="module-stats-label">
    Empty Sections:
    <b><?php $totalEmpty = $employmentHistoryManager->getEmptySections(); echo $totalEmpty;?></b>
</div>
<div class="module-stats-label">
    Partially Completed Sections:
    <b><?php echo $totalSection - $totalCompleted - $totalEmpty;?></b>
</div>
