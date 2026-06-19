<div
    class="modal fade"
    id="createUserModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="fas fa-user-plus"></i>

                    Ajouter un utilisateur

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>

            {{-- ================= ERRORS ================= --}}
            @if ($errors->any())

                <div class="alert alert-danger m-3">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            {{-- FORM --}}
            <form
                method="POST"
                action="{{ route('admin.users.store') }}"
            >

                @csrf

                <div class="modal-body">

                    {{-- NAME --}}
                    <div class="mb-3">

                        <label class="form-label">Nom complet</label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            required
                        >

                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">

                        <label class="form-label">Email</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email') }}"
                            required
                        >

                    </div>

                    {{-- SEXE --}}
                    <div class="mb-3">

                        <label class="form-label">Sexe</label>

                        <select name="sexe" class="form-select" required>
                            <option value="">Sélectionner...</option>
                            <option value="Homme" {{ old('sexe') === 'Homme' ? 'selected' : '' }}>Homme</option>
                            <option value="Femme" {{ old('sexe') === 'Femme' ? 'selected' : '' }}>Femme</option>
                        </select>

                    </div>

                    {{-- INVITATION (plus de mot de passe à saisir) --}}
                    <div class="alert alert-info d-flex align-items-start gap-2 py-2">
                        <i class="fas fa-envelope mt-1"></i>
                        <div class="small">
                            Aucun mot de passe à saisir : à la création, un
                            <strong>email d'invitation</strong> est envoyé à l'utilisateur
                            avec un lien sécurisé pour qu'il définisse lui-même son mot de passe.
                        </div>
                    </div>

                    {{-- ROLE --}}
                    <div class="mb-3">

                        <label class="form-label">Rôle</label>

                        <select
                            name="role"
                            class="form-select"
                            id="createUserRole"
                            required
                        >

                            <option value="">Sélectionner...</option>

                            <optgroup label="Membres">
                                <option value="Étudiant">Étudiant</option>
                                <option value="Prof">Prof</option>
                                <option value="Fonctionnaire">Fonctionnaire</option>
                                <option value="Externe">Externe</option>
                            </optgroup>

                            <optgroup label="Personnel">
                                <option value="Bibliothécaire">Bibliothécaire</option>
                                <option value="Administrateur">Administrateur</option>
                                <option value="Administration">Administration</option>
                            </optgroup>

                        </select>

                    </div>

                    {{-- MATRICULE (Étudiant) --}}
                    <div
                        class="mb-3"
                        id="matriculeField"
                        style="display:none;"
                    >

                        <label class="form-label">Matricule</label>

                        <input
                            type="text"
                            name="matricule"
                            class="form-control"
                            placeholder="Ex: 22A145FS"
                            value="{{ old('matricule') }}"
                        >

                    </div>

                    {{-- CHAMPS EXTERNE (téléphone + pièce d'identité) --}}
                    <div id="externeFields" style="display:none;">

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input
                                type="text"
                                name="telephone"
                                class="form-control"
                                placeholder="Ex: 06 12 34 56 78"
                                value="{{ old('telephone') }}"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">N° pièce d'identité (CIN / passeport)</label>
                            <input
                                type="text"
                                name="numero_piece"
                                class="form-control"
                                placeholder="Déposée en garantie au retrait"
                                value="{{ old('numero_piece') }}"
                            >
                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
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
                        class="btn btn-primary"
                    >
                        Ajouter
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- =========================
     SCRIPT
========================= --}}
<script>

    const roleSelect =
        document.getElementById("createUserRole");

    const matriculeField =
        document.getElementById("matriculeField");

    const externeFields =
        document.getElementById("externeFields");

    function toggleRoleFields() {

        // Matricule : uniquement pour Étudiant
        matriculeField.style.display =
            roleSelect.value === "Étudiant" ? "block" : "none";

        // Téléphone + pièce : uniquement pour Externe
        externeFields.style.display =
            roleSelect.value === "Externe" ? "block" : "none";
    }

    roleSelect?.addEventListener("change", toggleRoleFields);

    // 🔥 RESTORE STATE AFTER VALIDATION ERROR
    document.addEventListener("DOMContentLoaded", function () {

        toggleRoleFields();

        @if ($errors->any())

            let modal = new bootstrap.Modal(document.getElementById('createUserModal'));
            modal.show();

        @endif

        // ⏳ auto hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.display = 'none';
            });
        }, 5000);

    });

</script>