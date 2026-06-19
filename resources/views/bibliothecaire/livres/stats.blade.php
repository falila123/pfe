<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico primary"><i class="fas fa-book"></i></div>
        <div>
            <div class="kpi-val" id="totalLivres">{{ $totalLivres }}</div>
            <div class="kpi-lab">Total livres</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-layer-group"></i></div>
        <div>
            <div class="kpi-val" id="totalExemplaires">{{ $totalExemplaires }}</div>
            <div class="kpi-lab">Total exemplaires</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="kpi-val" id="livresDisponibles">{{ $livresDisponibles }}</div>
            <div class="kpi-lab">Disponibles</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-times-circle"></i></div>
        <div>
            <div class="kpi-val" id="livresIndisponibles">{{ $livresIndisponibles }}</div>
            <div class="kpi-lab">Indisponibles</div>
        </div>
    </div>

</div>
