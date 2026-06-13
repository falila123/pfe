<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stat-card primary">
            <i class="fas fa-book-reader"></i>
            <div class="stat-number">{{ $totalEmprunts }}</div>
            <div>Total emprunts</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <i class="fas fa-clock"></i>
            <div class="stat-number">{{ $empruntsEnCours }}</div>
            <div>En cours</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="stat-number">{{ $empruntsRetard }}</div>
            <div>En retard</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <i class="fas fa-check"></i>
            <div class="stat-number">{{ $empruntsRetournes }}</div>
            <div>Retournés</div>
        </div>
    </div>

</div>
