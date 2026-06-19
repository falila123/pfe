<div class="modal fade"
     id="addLivreModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-book me-2"></i>
                    Ajouter un livre
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

             <form id="addLivreForm"
      method="POST"
      action="{{ route('livres.store') }}"
      enctype="multipart/form-data">

@csrf

{{-- ================= ROW 1 ================= --}}
<div class="row">

    <div class="col-md-6 mb-2">
        <label class="form-label">Titre</label>
        <input type="text" class="form-control" name="titre">
    </div>

    <div class="col-md-6 mb-2">
        <label class="form-label">Couverture</label>
        <input type="file" class="form-control" name="couverture" accept="image/*">
        <input type="hidden" name="couverture_url" id="couvertureUrl">

        <div id="coverPreviewWrap" style="display:none; margin-top:8px;">
            <img id="coverPreview" alt="Aperçu couverture"
                 style="max-height:120px; border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.15);">
            <small class="text-muted d-block">
                Couverture récupérée automatiquement, elle sera enregistrée à la création
                (ou choisissez un fichier pour la remplacer).
            </small>
        </div>
    </div>

</div>

{{-- ================= ROW 2 ================= --}}
<div class="row">

    <div class="col-md-5 mb-2">
        <label class="form-label">ISBN</label>

        <div class="input-group">
            <span class="input-group-text">978-</span>
            <input type="text" class="form-control" name="isbn" maxlength="10">
        </div>
    </div>

    <div class="col-md-4 mb-2">
        <label class="form-label">Cote livre</label>
        <input type="text" class="form-control" id="cotePreview" name="cote" readonly>
    </div>

    <div class="col-md-3 mb-2">
        <label class="form-label">Nombre d'exemplaires</label>
        <input type="number" class="form-control" name="nombre_exemplaires"
               value="1" min="1" max="50">
    </div>

</div>

{{-- ================= ROW 3 ================= --}}
<div class="row">

    <div class="col-md-4 mb-2">
        <label class="form-label">Catégorie</label>
        <select class="form-select" id="categorie" name="categorie" onchange="loadSousCategories()">
            <option value="Littérature">Littérature</option>
            <option value="Informatique">Informatique</option>
            <option value="Mathématiques">Mathématiques</option>
            <option value="Science">Science</option>
            <option value="Histoire">Histoire</option>
        </select>
    </div>

    <div class="col-md-4 mb-2">
        <label class="form-label">Sous-catégorie Dewey</label>
        <select class="form-select" id="subCategorie" name="sous_categorie_dewey"
                onchange="updateTypeFromSubCategory(); generateCote()"></select>
    </div>

    <div class="col-md-4 mb-2">
        <label class="form-label">Type</label>
        <select class="form-select" id="typeCode" name="type_livre" onchange="generateCote()">
            <option value="ROM">Roman</option>
            <option value="POE">Poésie</option>
            <option value="THE">Théâtre</option>
            <option value="ESS">Essai</option>
            <option value="PRO">Programmation</option>
            <option value="WEB">Web Development</option>
            <option value="DATA">Data Science</option>
            <option value="DEV">DevOps</option>
            <option value="SEC">Sécurité</option>
            <option value="ALG">Algèbre</option>
            <option value="GEO">Géométrie</option>
            <option value="CAL">Calcul</option>
            <option value="STA">Statistiques</option>
            <option value="TOP">Topologie</option>
            <option value="PHY">Physique</option>
            <option value="CHI">Chimie</option>
            <option value="BIO">Biologie</option>
            <option value="ECO">Écologie</option>
            <option value="AST">Astronomie</option>
            <option value="HIS">Histoire Générale</option>
            <option value="ANC">Histoire Ancienne</option>
            <option value="MOY">Moyen Âge</option>
            <option value="MOD">Histoire Moderne</option>
            <option value="CON">Histoire Contemporaine</option>
        </select>
    </div>

</div>

{{-- ================= ROW 4 ================= --}}
<div class="row">

    <div class="col-md-4 mb-2">
        <label class="form-label">Langue</label>
        <select class="form-select" name="langue" onchange="generateCote()">
            <option value="FR">Français</option>
            <option value="EN">Anglais</option>
            <option value="ES">Espagnol</option>
            <option value="DE">Allemand</option>
        </select>
    </div>

    <div class="col-md-4 mb-2">
        <label class="form-label">Auteurs</label>
        <select class="form-select" multiple id="auteurs" name="auteurs[]">
            @foreach($auteurs as $auteur)
                <option value="{{ $auteur->id }}">{{ $auteur->nom }}</option>
            @endforeach
        </select>
        <small id="auteursSuggestion" class="text-muted d-block mt-1"></small>
    </div>

    <div class="col-md-4 mb-2">
        <label class="form-label">Ajouter auteur</label>

        <div class="d-flex gap-2">
            <input type="text" class="form-control" id="newAuteur">
            <button type="button" class="btn btn-outline-primary" onclick="addAuteur()">+</button>
        </div>
    </div>

</div>

{{-- ================= DESCRIPTION ================= --}}
<div class="mb-2">
    <label class="form-label">Description</label>
    <textarea class="form-control" rows="2" name="description"></textarea>
</div>

</form>

            </div>

            <div class="modal-footer">

                <button type="button"
                        id="btnGenerer"
                        class="btn btn-success me-auto"
                        onclick="genererInfosLivre()">
                    <i class="fas fa-wand-magic-sparkles me-1"></i> Générer
                </button>

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Annuler
                </button>

                <button type="submit"
                    form="addLivreForm"
                    class="btn btn-primary">
                Enregistrer
            </button>

            </div>

        </div>

    </div>

</div>

<script>
async function genererInfosLivre() {

    const form  = document.getElementById('addLivreForm');

    // Le champ ISBN ne contient que les 10 chiffres après « 978- » → on reconstruit l'ISBN complet
    const isbnSuffix = form.querySelector('[name="isbn"]').value.trim();
    const isbn  = isbnSuffix ? ('978' + isbnSuffix) : '';
    const titre = form.querySelector('[name="titre"]').value.trim();

    if (!isbn && !titre) {
        alert("Saisis d'abord un ISBN (de préférence) ou au moins le titre.");
        return;
    }

    const btn = document.getElementById('btnGenerer');
    const oldHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Recherche...';

    try {
        const url = "{{ route('bibliothecaire.livres.lookup') }}"
            + "?isbn=" + encodeURIComponent(isbn)
            + "&titre=" + encodeURIComponent(titre);

        const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await resp.json();

        if (!data.found) {
            alert("Aucune information trouvée pour ce livre. Tu peux saisir les champs manuellement.");
            return;
        }

        // Titre (uniquement s'il est vide)
        const titreInput = form.querySelector('[name="titre"]');
        if (!titreInput.value.trim() && data.titre) {
            titreInput.value = data.titre;
        }

        // Description
        if (data.description) {
            form.querySelector('[name="description"]').value = data.description;
        }

        // Aperçu + couverture à enregistrer automatiquement
        const wrap   = document.getElementById('coverPreviewWrap');
        const img    = document.getElementById('coverPreview');
        const hidden = document.getElementById('couvertureUrl');
        if (data.couverture) {
            img.src = data.couverture;
            hidden.value = data.couverture;
            wrap.style.display = 'block';
        } else {
            hidden.value = '';
            wrap.style.display = 'none';
        }

        // Suggestion d'auteurs
        const sugg = document.getElementById('auteursSuggestion');
        if (data.auteurs && data.auteurs.length) {
            sugg.innerHTML = 'Auteur(s) suggéré(s) : <strong>'
                + data.auteurs.join(', ')
                + '</strong>, ajoutez-les via le champ : « Ajouter auteur ».';
        } else {
            sugg.innerHTML = '';
        }

        // Langue (mapping code API → ta liste)
        if (data.langue) {
            const map = { fr: 'FR', en: 'EN', es: 'ES', de: 'DE' };
            const code = map[data.langue.toLowerCase().slice(0, 2)];
            if (code) {
                const langueSel = form.querySelector('[name="langue"]');
                langueSel.value = code;
                langueSel.dispatchEvent(new Event('change')); // met à jour la cote
            }
        }

    } catch (e) {
        alert("Erreur lors de la récupération des informations. Réessaie ou saisis manuellement.");
    } finally {
        btn.disabled = false;
        btn.innerHTML = oldHtml;
    }
}
</script>