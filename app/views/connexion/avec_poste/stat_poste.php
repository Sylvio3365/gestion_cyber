<!-- Stats Section -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card active">
            <div class="stat-icon">
                <i class="bi bi-wifi"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $stats['actifs'] ?></h3>
                <p class="stat-label">PC Actifs</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card available">
            <div class="stat-icon">
                <i class="bi bi-display"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $stats['disponibles'] ?></h3>
                <p class="stat-label">PC Disponibles</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card maintenance">
            <div class="stat-icon">
                <i class="bi bi-tools"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $stats['maintenance'] ?></h3>
                <p class="stat-label">En Maintenance</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card occupation">
            <div class="stat-icon">
                <i class="bi bi-pie-chart"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= $stats['occupation'] ?>%</h3>
                <p class="stat-label">Taux d'occupation</p>
            </div>
        </div>
    </div>
</div>