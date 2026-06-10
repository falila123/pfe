<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ajouter un livre
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">

                <!-- FORM CONNECTÉ LARAVEL -->
                <form method="POST" action="{{ route('livres.store') }}" id="addBookForm">

                    @csrf

                    <div class="row g-3">

                        <!-- TITRE -->
                        <div class="col-md-6">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" name="titre" placeholder="Entrez le titre" required>
                        </div>

                        <!-- COTE -->
                        <div class="col-md-6">
                            <label class="form-label">Cote livre</label>
                            <input type="text" class="form-control" name="cote" readonly>
                        </div>

                        <!-- ISBN -->
                        <div class="col-md-6">
                            <label class="form-label">ISBN</label>
                            <div class="input-group">
                                <span class="input-group-text">978-</span>
                                <input type="text" class="form-control" name="isbn">
                            </div>
                        </div>

                        <!-- CATEGORIE -->
                        <div class="col-md-6">
                            <label class="form-label">Catégorie</label>
                            <select class="form-select" name="categorie">
                                <option>Littérature</option>
                                <option>Informatique</option>
                                <option>Mathématiques</option>
                                <option>Science</option>
                                <option>Histoire</option>
                            </select>
                        </div>

                        <!-- DEWEY -->
                        <div class="col-md-4">
                            <label class="form-label">Sous-catégorie Dewey</label>
                            <input type="text" class="form-control" name="sous_categorie_dewey">
                        </div>

                        <!-- TYPE -->
                        <div class="col-md-4">
                            <label class="form-label">Type Littéraire / Technique</label>
                            <select class="form-select" name="type_livre">
                                <option>Roman</option>
                                <option>Poésie</option>
                                <option>Essai</option>
                                <option>Programmation</option>
                                <option>Data Science</option>
                                <option>DevOps</option>
                                <option>Algèbre</option>
                                <option>Physique</option>
                                <option>Histoire</option>
                            </select>
                        </div>

                        <!-- LANGUE -->
                        <div class="col-md-4">
                            <label class="form-label">Langue</label>
                            <select class="form-select" name="langue">
                                <option>Français</option>
                                <option>Anglais</option>
                                <option>Espagnol</option>
                                <option>Allemand</option>
                            </select>
                        </div>

                        <!-- AUTEURS -->
                        <div class="col-md-6">
                            <label class="form-label">Auteurs</label>
                            <select class="form-select" name="auteurs[]" multiple>
                                <option>Exemple Auteur</option>
                            </select>

                            <small class="text-muted">
                                Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs auteurs
                            </small>
                        </div>

                        <!-- AJOUT AUTEUR -->
                        <div class="col-md-6">
                            <label class="form-label">Ajouter auteur</label>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" name="new_auteur" placeholder="Ajouter un auteur">
                                <button type="button" class="btn btn-outline-primary">+</button>
                            </div>
                        </div>

                        <!-- COUVERTURE -->
                        <div class="col-md-4">
                            <label class="form-label">Couverture du livre</label>

                            <img src="https://clipart-library.com/images_k/books-clipart-transparent/books-clipart-transparent-10.png"
                                 style="width:120px;height:170px;object-fit:cover;border-radius:10px;border:1px solid #ddd;">

                            <small class="text-muted d-block mt-2">
                                Image automatique ou image par défaut
                            </small>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="col-md-8">
                            <label class="form-label">Description</label>

                            <textarea class="form-control" name="description" rows="7"
                                      placeholder="Description générée automatiquement"></textarea>
                        </div>

                    </div>

                    <!-- BUTTONS -->
                    <div class="mt-4 d-flex justify-content-between">

                        <button type="button" class="btn btn-secondary">
                            Annuler
                        </button>

                        <div class="d-flex gap-2">

                            <button type="button" class="btn btn-outline-primary">
                                Générer
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Ajouter
                            </button>

                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>

</x-app-layout>