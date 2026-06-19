<div class="kpi-row">

    {{-- Total --}}
    <div class="kpi-card">
        <div class="kpi-ico primary"><i class="fas fa-users"></i></div>
        <div>
            <div class="kpi-val">{{ $totalUsers }}</div>
            <div class="kpi-lab">Total utilisateurs</div>
        </div>
    </div>

    {{-- Membres --}}
    <div class="kpi-card">
        <div class="kpi-ico info"><i class="fas fa-user-group"></i></div>
        <div>
            <div class="kpi-val">{{ $membres }}</div>
            <div class="kpi-lab">Membres</div>
        </div>
    </div>

    {{-- Personnel --}}
    <div class="kpi-card">
        <div class="kpi-ico success"><i class="fas fa-user-tie"></i></div>
        <div>
            <div class="kpi-val">{{ $personnel }}</div>
            <div class="kpi-lab">Personnel</div>
        </div>
    </div>

    {{-- Étudiants --}}
    <div class="kpi-card">
        <div class="kpi-ico warning"><i class="fas fa-user-graduate"></i></div>
        <div>
            <div class="kpi-val">{{ $etudiants }}</div>
            <div class="kpi-lab">Étudiants</div>
        </div>
    </div>

</div>
