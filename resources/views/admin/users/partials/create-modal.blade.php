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

                    {{-- PASSWORD --}}
                    <div class="mb-3">

                        <label class="form-label">Mot de passe</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

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

                            <option value="Étudiant">Étudiant</option>

                            <option value="Bibliothécaire">Bibliothécaire</option>

                            <option value="Administrateur">Administrateur</option>

                            <option value="Administration">Administration</option>

                        </select>

                    </div>

                    {{-- MATRICULE --}}
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

    function toggleMatricule() {

        if (roleSelect.value === "Étudiant") {
            matriculeField.style.display = "block";
        } else {
            matriculeField.style.display = "none";
        }
    }

    roleSelect?.addEventListener("change", toggleMatricule);

    // 🔥 RESTORE STATE AFTER VALIDATION ERROR
    document.addEventListener("DOMContentLoaded", function () {

        toggleMatricule();

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