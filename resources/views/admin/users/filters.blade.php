
<form method="GET" action="{{ route('admin.users.index') }}">

    <div class="search-add-card d-flex align-items-center gap-2 mb-4 p-3">

        {{-- SEARCH --}}
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="🔎 Rechercher un utilisateur..."
            value="{{ request('search') }}"
        >

        {{-- ROLE --}}
        <select
            name="role"
            class="form-select"
        >
            <option value="">
                Tous les rôles
            </option>

            <option value="Étudiant"
                {{ request('role') === 'Étudiant' ? 'selected' : '' }}>
                Étudiant
            </option>

            <option value="Bibliothécaire"
                {{ request('role') === 'Bibliothécaire' ? 'selected' : '' }}>
                Bibliothécaire
            </option>

            <option value="Administrateur"
                {{ request('role') === 'Administrateur' ? 'selected' : '' }}>
                Administrateur
            </option>

            <option value="Administration"
                {{ request('role') === 'Administration' ? 'selected' : '' }}>
                Administration
            </option>

        </select>

        {{-- STATUS --}}
        <select
            name="status"
            class="form-select"
        >
            <option value="">
                Tous les statuts
            </option>

            <option value="actif"
                {{ request('status') === 'actif' ? 'selected' : '' }}>
                Actifs
            </option>

            <option value="inactif"
                {{ request('status') === 'inactif' ? 'selected' : '' }}>
                Désactivés
            </option>

        </select>

        {{-- SEARCH BUTTON --}}
        <button class="btn btn-primary">

            <i class="fas fa-search me-1"></i>

            Rechercher

        </button>

        {{-- RESET --}}
        <a
            href="{{ route('admin.users.index') }}"
            class="btn btn-primary"
        >
            <i class="fas fa-rotate me-1"></i>

            Réinitialiser
        </a>

        {{-- ADD USER --}}
       
<button
    type="button"
    class="btn btn-primary"
    data-bs-toggle="modal"
    data-bs-target="#createUserModal"
>

            <i class="fas fa-user-plus me-1"></i>

            Ajouter

        </button>

    </div>

</form>

