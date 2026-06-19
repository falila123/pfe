<div class="modal fade" id="validerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <form method="POST" id="validerForm">
        @csrf
        @method('PATCH')

        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-check"></i> Valider la demande</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <p class="mb-0">
            Vous validez la demande de <strong id="validerEtudiant"></strong>
            pour le livre <strong id="validerLivre"></strong>.
          </p>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Annuler
          </button>
          <button type="submit" class="btn btn-success">
            Valider (réserver 24 h)
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
function openValiderModal(demandeId, livreTitre, etudiantNom){

    const form = document.getElementById('validerForm');

    // route nommée avec placeholder remplacé par l'id réel
    form.action = "{{ route('bibliothecaire.demandes.accepter', ':id') }}"
        .replace(':id', demandeId);

    document.getElementById('validerLivre').innerText = livreTitre;
    document.getElementById('validerEtudiant').innerText = etudiantNom;

    new bootstrap.Modal(document.getElementById('validerModal')).show();
}
</script>
