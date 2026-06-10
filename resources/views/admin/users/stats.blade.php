
<div class="row mb-4">

    {{-- Étudiants --}}
    <div class="col-md-3">

        <div class="stat-card stat-etudiant">

            <i class="fas fa-user-graduate stat-icon"></i>

            <h6>Étudiants</h6>

            <h3>{{ $etudiants }}</h3>

        </div>

    </div>

    {{-- Administration --}}
    <div class="col-md-3">

        <div class="stat-card stat-administration">

            <i class="fas fa-user-tie stat-icon"></i>

            <h6>Administration</h6>

            <h3>{{ $administration }}</h3>

        </div>

    </div>

    {{-- Bibliothécaires --}}
    <div class="col-md-3">

        <div class="stat-card stat-biblio">

            <i class="fas fa-book stat-icon"></i>

            <h6>Bibliothécaires</h6>

            <h3>{{ $bibliothecaires }}</h3>

        </div>

    </div>

    {{-- Administrateurs --}}
    <div class="col-md-3">

        <div class="stat-card stat-admin">

            <i class="fas fa-user-shield stat-icon"></i>

            <h6>Administrateurs</h6>

            <h3>{{ $administrateurs }}</h3>

        </div>

    </div>

</div>

