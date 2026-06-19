<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-book-reader"></i></div>
        <div>
            <div class="kpi-val">{{ $enCours }}</div>
            <div class="kpi-lab">En cours</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="kpi-val">{{ $enRetard }}</div>
            <div class="kpi-lab">En retard</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-check"></i></div>
        <div>
            <div class="kpi-val">{{ $retournes }}</div>
            <div class="kpi-lab">Retournés</div>
        </div>
    </div>

</div>
