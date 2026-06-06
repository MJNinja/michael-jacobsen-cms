<div class="module-stats-label">
    Total Events:
    <b><?php echo $eventManager->getTotalEvents();?></b>
</div>
<div class="module-stats-label">
    Active Events:
    <b><?php echo $eventManager->getActiveEvents();?></b>
</div>
<div class="module-stats-label">
    Pending Events:
    <b><?php echo $eventManager->getPendingEvents();?></b>
</div>
<div class="module-stats-label">
    Expired Events:
    <b><?php echo $eventManager->getTotalExpiredEvents();?></b>
</div>
<div class="module-stats-label">
    Removed Events:
    <b><?php echo $eventManager->getTotalRemovedEvents();?></b>
</div>
<div class="module-stats-label">
    Event Image Sizes:<br />
    Width: <strong><?php echo $eventWidth; ?>px</strong><br />
    Height: <strong><?php echo $eventHeight; ?>px</strong>
</div>
<div class="module-stats-label">
    Paragraph Image Sizes:<br />
    Width: <strong><?php echo $paragraphWidth; ?>px</strong><br />
    Height: <strong><?php echo $paragraphHeight; ?>px</strong>
</div>
