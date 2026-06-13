<div class="modal fade" id="borrowModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('bibliothecaire.emprunts.store') }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Enregistrer un emprunt</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="exemplaire_id" id="borrowExemplaireId">

          <div class="mb-3">
            <label class="form-label">Exemplaire</label>
            <input type="text" id="borrowExemplaireCode" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Matricule étudiant</label>
            <input type="text" name="matricule" id="studentCode" class="form-control"
                   placeholder="Ex: 22A145FS" oninput="verifyStudent()" required>
            <small id="studentInfo" class="mt-2 d-block"></small>
          </div>

          <div class="mb-3">
            <label class="form-label">Durée d'emprunt (jours)</label>
            <input type="number" name="duree" id="borrowDuration" class="form-control"
                   min="1" placeholder="Ex: 7" oninput="computeReturnDate()" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Date d'emprunt</label>
            <input type="text" id="borrowDate" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Date retour prévue</label>
            <input type="text" id="returnDate" class="form-control" readonly>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Confirmer l'emprunt</button>
        </div>

      </form>

    </div>
  </div>
</div>
