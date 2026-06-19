<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-hourglass-half"></i></div>
        <div>
            <div class="kpi-val">{{ $enAttente }}</div>
            <div class="kpi-lab">En attente</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="kpi-val">{{ $acceptees }}</div>
            <div class="kpi-lab">Acceptées</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-times-circle"></i></div>
        <div>
            <div class="kpi-val">{{ $refusees }}</div>
            <div class="kpi-lab">Refusées</div>
        </div>
    </div>

</div>
