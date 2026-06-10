<div class="modal fade"
     id="editLivreModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Modifier le livre
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="editLivreForm" method="POST">

                    @csrf
                    @method('PUT')

                    <input type="hidden" id="editLivreId">

                    {{-- ================= ROW 1 ================= --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="editTitre"
                                   name="titre">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Couverture</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="editCouverture"
                                   name="couverture" 
                                   accept="image/*">
                        </div>

                    </div>

                    {{-- ================= ROW 2 ================= --}}
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">ISBN</label>
                            <div class="input-group">
                                <span class="input-group-text">978-</span>
                                <input type="text" 
                                       class="form-control" 
                                       id="editIsbn"
                                       name="isbn"
                                       maxlength="10">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cote livre</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="editCote"
                                   name="cote"
                                   readonly>
                        </div>

                    </div>

                    {{-- ================= ROW 3 ================= --}}
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Catégorie</label>
                            <select class="form-select" 
                                    id="editCategorie" 
                                    name="categorie"
                                    onchange="loadSousCategories('edit'); generateCote('edit')">
                                <option value="Littérature">Littérature</option>
                                <option value="Informatique">Informatique</option>
                                <option value="Mathématiques">Mathématiques</option>
                                <option value="Science">Science</option>
                                <option value="Histoire">Histoire</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sous-catégorie Dewey</label>
                            <select class="form-select"
                                id="editSubCategorie"
                                name="sous_categorie_dewey"
                                onchange="updateTypeFromSubCategory('edit'); generateCote('edit')"></select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" 
                                    id="editTypeCode" 
                                    name="type_livre">
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

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Langue</label>
                            <select class="form-select"
                                id="editLangue"
                                name="langue"
                                onchange="generateCote('edit')">
                                <option value="FR">Français</option>
                                <option value="EN">Anglais</option>
                                <option value="ES">Espagnol</option>
                                <option value="DE">Allemand</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Auteurs</label>
                            <select class="form-select"
                                multiple
                                id="editAuteurs"
                                name="auteurs[]"
                                onchange="generateCote('edit')">
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Ajouter auteur</label>
                            <div class="d-flex gap-2">
                                <input type="text" 
                                       class="form-control" 
                                       id="editNewAuteur">
                                <button type="button" 
                                        class="btn btn-outline-primary" 
                                        onclick="addAuteur('edit')">+</button>
                            </div>
                        </div>

                    </div>

                    {{-- ================= DESCRIPTION ================= --}}
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" 
                                  rows="4" 
                                  id="editDescription"
                                  name="description"></textarea>
                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Annuler
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="saveEditLivreBtn">
                    Enregistrer
                </button>

            </div>

        </div>

    </div>

</div>