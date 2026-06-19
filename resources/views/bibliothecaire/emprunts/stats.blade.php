<div class="kpi-row">

    <div class="kpi-card">
        <div class="kpi-ico primary"><i class="fas fa-book-reader"></i></div>
        <div>
            <div class="kpi-val">{{ $totalEmprunts }}</div>
            <div class="kpi-lab">Total emprunts</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-clock"></i></div>
        <div>
            <div class="kpi-val">{{ $empruntsEnCours }}</div>
            <div class="kpi-lab">En cours</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico danger"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="kpi-val">{{ $empruntsRetard }}</div>
            <div class="kpi-lab">En retard</div>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-check"></i></div>
        <div>
            <div class="kpi-val">{{ $empruntsRetournes }}</div>
            <div class="kpi-lab">Retournés</div>
        </div>
    </div>

</div>
