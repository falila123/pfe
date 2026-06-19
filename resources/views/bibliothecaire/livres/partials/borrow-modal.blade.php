<div class="modal fade" id="borrowModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" action="{{ route('bibliothecaire.emprunts.store') }}" id="borrowForm">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-right-left me-2"></i>Enregistrer un emprunt</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="exemplaire_id" id="borrowExemplaireId">
          <input type="hidden" name="user_id" id="borrowUserId">

          {{-- Exemplaire concerné --}}
          <div class="mb-3">
            <label class="form-label">Exemplaire</label>
            <div id="borrowExemplaireCode" class="form-control bg-light"></div>
          </div>

          {{-- Recherche d'un membre --}}
          <div class="mb-2">
            <label class="form-label">Membre emprunteur</label>
            <input type="text" id="memberSearch" class="form-control"
                   placeholder="Rechercher par nom, email ou matricule..."
                   autocomplete="off" oninput="filterMembers()">
            <div id="memberResults" class="list-group mt-1"
                 style="max-height:190px; overflow:auto;"></div>
          </div>

          {{-- Membre sélectionné + récap --}}
          <div id="selectedMemberPanel" style="display:none;">

            <div class="border rounded p-2 mb-2">
              <strong id="selMemberName"></strong>
              <span class="badge bg-secondary" id="selMemberType"></span>
              <div><small class="text-muted" id="selMemberId"></small></div>
            </div>

            <div id="quotaWarning" class="alert alert-danger py-2 mb-2" style="display:none;"></div>

            <div class="row g-2" id="borrowDatesRow">
              <div class="col-4">
                <label class="form-label mb-0 text-muted small">Durée</label>
                <div id="lblDuree" class="fw-semibold"></div>
              </div>
              <div class="col-4">
                <label class="form-label mb-0 text-muted small">Date d'emprunt</label>
                <div id="lblDateEmprunt" class="fw-semibold"></div>
              </div>
              <div class="col-4">
                <label class="form-label mb-0 text-muted small">Retour prévu</label>
                <div id="lblDateRetour" class="fw-semibold"></div>
              </div>
            </div>

          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary" id="borrowConfirmBtn" disabled>
            Confirmer l'emprunt
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
