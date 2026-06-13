<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="stat-card primary">
            <i class="fas fa-book"></i>
            <div class="stat-number" id="totalLivres">{{ $totalLivres }}</div>
            <div>Total livres</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card success">
            <i class="fas fa-layer-group"></i>
            <div class="stat-number" id="totalExemplaires">{{ $totalExemplaires }}</div>
            <div>Total exemplaires</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card info">
            <i class="fas fa-check-circle"></i>
            <div class="stat-number" id="livresDisponibles">{{ $livresDisponibles }}</div>
            <div>Disponibles</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card danger">
            <i class="fas fa-times-circle"></i>
            <div class="stat-number" id="livresIndisponibles">{{ $livresIndisponibles }}</div>
            <div>Indisponibles</div>
        </div>
    </div>

</div>