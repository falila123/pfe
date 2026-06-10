<div
    class="modal fade"
    id="editUserModal"
    tabindex="-1"
>

    <div class="modal-dialog">

        <div class="modal-content">

            {{-- ================= ALERTS ================= --}}
            @if (session('success') || $errors->any())

                <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} m-3">

                    @if (session('success'))
                        {{ session('success') }}
                    @endif

                    @if ($errors->any())
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                </div>

            @endif

            <form
                method="POST"
                id="editUserForm"
            >

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-user-edit"></i>

                        Modifier un utilisateur

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">Nom complet</label>

                        <input
                            type="text"
                            name="name"
                            id="edit_name"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Email</label>

                        <input
                            type="email"
                            name="email"
                            id="edit_email"
                            class="form-control"
                            required
                        >

                    </div>

                    <div class="mb-3">

                        <label class="form-label">Rôle</label>

                        <select
                            name="role"
                            id="edit_role"
                            class="form-select"
                            required
                        >

                            <option value="Étudiant">Étudiant</option>

                            <option value="Bibliothécaire">Bibliothécaire</option>

                            <option value="Administrateur">Administrateur</option>

                            <option value="Administration">Administration</option>

                        </select>

                    </div>

                    <div
                        class="mb-3"
                        id="editMatriculeContainer"
                        style="display:none;"
                    >

                        <label class="form-label">Matricule</label>

                        <input
                            type="text"
                            name="matricule"
                            id="edit_matricule"
                            class="form-control"
                        >

                    </div>

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
                        class="btn btn-primary"
                    >
                        Enregistrer
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

{{-- ================= AUTO HIDE ALERT ================= --}}
<script>
    setTimeout(() => {

        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = "opacity 0.5s ease";
            el.style.opacity = "0";

            setTimeout(() => {
                el.remove();
            }, 500);
        });

    }, 5000);
</script>