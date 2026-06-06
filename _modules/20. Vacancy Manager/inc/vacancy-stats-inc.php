<div class="module-stats-label">
    Total Vacancies:
    <b><?php echo $vacancyManager->getTotalVacancies();?></b>
</div>

<div class="module-stats-label">
    Active Vacancies:
    <b><?php echo $vacancyManager->getTotalActiveVacancies();?></b>
</div>

<div class="module-stats-label">
    Pending Vacancies:
    <b><?php echo $vacancyManager->getTotalPendingVacancies();?></b>
</div>

<div class="module-stats-label">
    Expired Vacancies:
    <b><?php echo $vacancyManager->getTotalExpiredVacancies();?></b>
</div>
