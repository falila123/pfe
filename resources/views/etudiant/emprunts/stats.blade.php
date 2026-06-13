<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="stat-card info">
            <i class="fas fa-book-reader"></i>
            <div class="stat-number">{{ $enCours }}</div>
            <div>En cours</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div class="stat-number">{{ $enRetard }}</div>
            <div>En retard</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card success">
            <i class="fas fa-check"></i>
            <div class="stat-number">{{ $retournes }}</div>
            <div>Retournés</div>
        </div>
    </div>

</div>
