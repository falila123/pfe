<div class="modal fade" id="refuserModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" id="refuserForm">
        @csrf
        @method('PATCH')

        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-times"></i> Refuser la demande</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <p class="mb-3">
            Vous refusez la demande de <strong id="refuserEtudiant"></strong>
            pour le livre <strong id="refuserLivre"></strong>.
          </p>

          <div class="mb-2">
            <label class="form-label">Motif du refus (facultatif)</label>
            <textarea name="motif_refus"
                      class="form-control"
                      rows="3"
                      placeholder="Ex: Livre réservé pour un cours..."></textarea>
            <small class="text-muted">
              Laissez vide si aucun motif. L'étudiant verra ce message dans « Mes demandes ».
            </small>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Annuler
          </button>
          <button type="submit" class="btn btn-danger">
            Confirmer le refus
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
function openRefuserModal(demandeId, livreTitre, etudiantNom){

    const form = document.getElementById('refuserForm');

    form.action = "{{ route('bibliothecaire.demandes.refuser', ':id') }}"
        .replace(':id', demandeId);

    // reset du motif à chaque ouverture
    form.querySelector('[name="motif_refus"]').value = '';

    document.getElementById('refuserLivre').innerText = livreTitre;
    document.getElementById('refuserEtudiant').innerText = etudiantNom;

    new bootstrap.Modal(document.getElementById('refuserModal')).show();
}
</script>
