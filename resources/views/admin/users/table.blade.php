
<div class="table-section">

    <h4 class="mb-3">
        Liste des utilisateurs
    </h4>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Nom</th>

                    <th>Email</th>

                    <th>Matricule</th>

                    <th>Rôle</th>

                    <th>Date création</th>

                    <th>Actions</th>

                </tr>

            </thead>

            <tbody>

                @forelse($users as $user)          

@php

    $badgeClass = match(trim($user->role)) {

        'Étudiant' => 'badge-etudiant',

        'Bibliothécaire' => 'badge-bibliothecaire',

        'Administrateur' => 'badge-administrateur',

        'Administration' => 'badge-administration',

        default => 'badge-administrateur'
    };

@endphp

                    <tr
                        class="{{ $user->status === 'inactif'
                            ? 'inactive-user'
                            : '' }}"
                    >

                        {{-- NAME --}}
                        <td>

                            {{ $user->name }}

                        </td>

                        {{-- EMAIL --}}
                        <td>

                            {{ $user->email }}

                        </td>

                        {{-- MATRICULE --}}
                        <td>

                            {{ $user->matricule ?? '-' }}

                        </td>

                        {{-- ROLE --}}
                        <td>

                            <span class="badge-role {{ $badgeClass }}">

                                {{ $user->role }}

                            </span>

                        </td>

                        {{-- DATE --}}
                        <td>

                            {{ $user->created_at->format('d/m/Y') }}

                        </td>

                        {{-- ACTIONS --}}
                        <td>

                            <div class="action-menu">

                                {{-- 3 DOTS --}}
                                <div
                                    class="menu-dots"
                                    onclick="toggleUserMenu(this)"
                                >

                                    <i class="fas fa-ellipsis-h"></i>

                                </div>

                                {{-- DROPDOWN --}}
                                <div class="dropdown-menu-user">

                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        class="edit open-edit-modal"

                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}"
                                        data-matricule="{{ $user->matricule }}"

                                        data-bs-toggle="modal"
                                        data-bs-target="#editUserModal"
                                    >

                                        <i class="fas fa-edit"></i>

                                        Modifier

                                    </button>

                                    {{-- STATUS --}}
                                    <button
                                    type="button"
                                    class="{{ $user->status === 'actif'
                                        ? 'delete'
                                        : 'edit' }} open-status-modal"

                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-status="{{ $user->status }}"

                                    data-bs-toggle="modal"
                                    data-bs-target="#statusModal"
                                >

                                    <i class="fas {{ $user->status === 'actif'
                                        ? 'fa-ban'
                                        : 'fa-check' }}">
                                    </i>

                                    {{ $user->status === 'actif'
                                        ? 'Désactiver'
                                        : 'Activer'
                                    }}

                                </button>

                                    {{-- RESET PASSWORD --}}
                                    <button
                                        type="button"
                                        class="edit open-reset-modal"

                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"

                                        data-bs-toggle="modal"
                                        data-bs-target="#resetPasswordModal"
                                    >

                                        <i class="fas fa-key"></i>

                                        Réinitialiser MDP

                                    </button>

                                </div>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center text-muted py-4">

                            Aucun utilisateur trouvé.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-4">

        {{ $users->links() }}

    </div>

</div>

<div
    class="modal fade"
    id="statusModal"
    tabindex="-1"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                id="statusForm"
            >

                @csrf
                @method('PATCH')

                <div class="modal-header">

                    <h5 class="modal-title">

                        Confirmation

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <p id="statusMessage"></p>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Annuler

                    </button>

                    <button
                        type="submit"
                        id="statusConfirmBtn"
                        class="btn btn-danger"
                    >

                        Confirmer

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- =========================
     RESET PASSWORD MODAL
========================= --}}
<div class="modal fade" id="resetPasswordModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" id="resetPasswordForm">

                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key"></i> Réinitialiser le mot de passe
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <p>
                        Définir un nouveau mot de passe pour
                        <strong id="resetUserName"></strong>.
                    </p>

                    <div class="mb-2">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input
                            type="text"
                            name="password"
                            class="form-control"
                            minlength="4"
                            required
                        >
                        <small class="text-muted">
                            Communiquez-le à l'utilisateur ; il pourra le modifier ensuite depuis son profil.
                        </small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Réinitialiser
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

{{-- =========================
     DROPDOWN SCRIPT
========================= --}}
<script>

    function toggleUserMenu(element){

        document
            .querySelectorAll(".dropdown-menu-user")
            .forEach(menu => {

                if(menu !== element.nextElementSibling){

                    menu.style.display = "none";
                }
            });

        const menu = element.nextElementSibling;

        menu.style.display =
            menu.style.display === "flex"
                ? "none"
                : "flex";
    }

    document.addEventListener("click", function(e){

        if(!e.target.closest(".action-menu")){

            document
                .querySelectorAll(".dropdown-menu-user")
                .forEach(menu => {

                    menu.style.display = "none";
                });
        }
    });

    /*
    |--------------------------------------------------------------------------
    | EDIT USER MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener('DOMContentLoaded', function () {

        document
            .querySelectorAll('.open-edit-modal')
            .forEach(button => {

                button.addEventListener('click', function () {

                    document.getElementById('edit_name').value =
                        this.dataset.name;

                    document.getElementById('edit_email').value =
                        this.dataset.email;

                    document.getElementById('edit_role').value =
                        this.dataset.role;

                    document.getElementById('edit_matricule').value =
                        this.dataset.matricule ?? '';

                    document.getElementById('editUserForm').action =
                        '/admin/users/' + this.dataset.id;

                    toggleEditMatricule();
                });

            });

    });

    /*
    |--------------------------------------------------------------------------
    | SHOW / HIDE MATRICULE
    |--------------------------------------------------------------------------
    */

    function toggleEditMatricule()
    {
        const role =
            document.getElementById('edit_role').value;

        const container =
            document.getElementById(
                'editMatriculeContainer'
            );

        if(role === 'Étudiant')
        {
            container.style.display = 'block';
        }
        else
        {
            container.style.display = 'none';
        }
    }

    document
        .getElementById('edit_role')
        ?.addEventListener(
            'change',
            toggleEditMatricule
        );


/*
|--------------------------------------------------------------------------
| STATUS MODAL
|--------------------------------------------------------------------------
*/

document
    .querySelectorAll('.open-status-modal')
    .forEach(button => {

        button.addEventListener('click', function () {

            const id =
                this.dataset.id;

            const name =
                this.dataset.name;

            const status =
                this.dataset.status;

            document
                .getElementById('statusForm')
                .action =
                    '/admin/users/' +
                    id +
                    '/toggle';

            document
                .getElementById('statusMessage')
                .innerText =
                    status === 'actif'
                    ? `Voulez-vous vraiment désactiver ${name} ?`
                    : `Voulez-vous vraiment réactiver ${name} ?`;

            const confirmBtn =
                document.getElementById(
                    'statusConfirmBtn'
                );

            if(status === 'actif')
            {
                confirmBtn.className =
                    'btn btn-danger';

                confirmBtn.innerText =
                    'Désactiver';
            }
            else
            {
                confirmBtn.className =
                    'btn btn-success';

                confirmBtn.innerText =
                    'Activer';
            }

        });

    });

/*
|--------------------------------------------------------------------------
| RESET PASSWORD MODAL
|--------------------------------------------------------------------------
*/

document
    .querySelectorAll('.open-reset-modal')
    .forEach(button => {

        button.addEventListener('click', function () {

            document.getElementById('resetPasswordForm').action =
                '/admin/users/' + this.dataset.id + '/reset-password';

            document.getElementById('resetUserName').innerText =
                this.dataset.name;
        });

    });

</script>

