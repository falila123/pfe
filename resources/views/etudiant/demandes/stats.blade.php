<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="stat-card info">
            <i class="fas fa-hourglass-half"></i>
            <div class="stat-number">{{ $enAttente }}</div>
            <div>En attente</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card success">
            <i class="fas fa-check-circle"></i>
            <div class="stat-number">{{ $acceptees }}</div>
            <div>Acceptées</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card danger">
            <i class="fas fa-times-circle"></i>
            <div class="stat-number">{{ $refusees }}</div>
            <div>Refusées</div>
        </div>
    </div>

</div>
