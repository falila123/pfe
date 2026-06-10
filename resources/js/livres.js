console.log('livres.js chargé');

/* =========================
   INIT
========================= */

function initLivres() {
    renderKPI();
    renderCards();
    renderTable();
}

function renderKPI() {
    const els = [
        "totalLivres",
        "totalExemplaires",
        "livresDisponibles",
        "livresIndisponibles"
    ];

    els.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerText = 0;
    });
}

function renderCards() {
  return;
}

function renderTable() {
    const el = document.getElementById("livresTableBody");
    if (el) el.innerHTML = "";
}

/* =========================
   DATA
========================= */

const sousCategories = {
    "Littérature": ["800", "810", "820", "830", "840"],
    "Informatique": ["004", "004.5", "004.51", "004.55", "004.56", "004.6", "004.7"],
    "Mathématiques": ["510", "511", "512", "513", "514", "515", "516"],
    "Science": ["500", "501", "502", "503", "504", "530", "540", "550"],
    "Histoire": ["900", "901", "902", "903", "904"]
};

const deweyMapping = {
    "800": "ROM",
    "810": "ROM",
    "820": "POE",
    "830": "THE",
    "840": "ESS",

    "004": "PRO",
    "004.5": "PRO",
    "004.51": "PRO",
    "004.55": "DATA",
    "004.56": "WEB",
    "004.6": "SEC",
    "004.7": "DEV",

    "510": "ALG",
    "511": "GEO",
    "512": "CAL",
    "513": "STA",
    "514": "TOP",

    "500": "PHY",
    "501": "CHI",
    "502": "BIO",
    "503": "ECO",
    "504": "AST",

    "900": "HIS",
    "901": "ANC",
    "902": "MOY",
    "903": "MOD",
    "904": "CON"
};

/* =========================
   SOUS-CATEGORIES
========================= */

window.loadSousCategories = function (mode = '') {

    const categorieId = mode === 'edit'
        ? 'editCategorie'
        : 'categorie';

    const sousCategorieId = mode === 'edit'
        ? 'editSubCategorie'
        : 'subCategorie';

    const typeId = mode === 'edit'
        ? 'editTypeCode'
        : 'typeCode';

    const categorie = document.getElementById(categorieId)?.value;
    const selectSous = document.getElementById(sousCategorieId);
    const selectType = document.getElementById(typeId);

    if (!categorie || !selectSous) return;

    selectSous.innerHTML = '';

    sousCategories[categorie].forEach(code => {
        selectSous.innerHTML += `<option value="${code}">${code}</option>`;
    });

    const firstCode = sousCategories[categorie][0];

    if (firstCode) {

        selectSous.value = firstCode;

        if (deweyMapping[firstCode] && selectType) {
            selectType.value = deweyMapping[firstCode];
        }
    }
};

window.updateTypeFromSubCategory = function (mode = '') {

    const subCategorieId = mode === 'edit'
        ? 'editSubCategorie'
        : 'subCategorie';

    const typeId = mode === 'edit'
        ? 'editTypeCode'
        : 'typeCode';

    const subCat = document.getElementById(subCategorieId)?.value;
    const selectType = document.getElementById(typeId);

    if (subCat && deweyMapping[subCat] && selectType) {
        selectType.value = deweyMapping[subCat];
    }
};

/* =========================
   AUTEUR ID FORMAT
========================= */

function formatAuteurId(id) {
    return String(id).padStart(6, '0');
}

/* =========================
   COTE
========================= */

window.generateCote = function (mode = '') {

    const subCategorieId =
        mode === 'edit' ? 'editSubCategorie' : 'subCategorie';

    const typeId =
        mode === 'edit' ? 'editTypeCode' : 'typeCode';

    const auteursId =
        mode === 'edit' ? 'editAuteurs' : 'auteurs';

    const coteId =
        mode === 'edit' ? 'editCote' : 'cotePreview';

    const langueSelector =
        mode === 'edit'
            ? '#editLangue'
            : '[name="langue"]';

    const sousCategorie =
        document.getElementById(subCategorieId)?.value || '';

    const type =
        document.getElementById(typeId)?.value || '';

    const langue =
        document.querySelector(langueSelector)?.value || '';

    const auteursSelect =
        document.getElementById(auteursId);

    const auteurs =
        Array.from(auteursSelect?.selectedOptions || [])
            .map(opt => opt.value);

    const auteurId =
        auteurs.length > 0
            ? formatAuteurId(auteurs[0])
            : '000000';

    const numeroLivre = '001';

    const cote =
        `${sousCategorie} ${auteurId} ${type} ${langue} ${numeroLivre}`;

    const coteInput =
        document.getElementById(coteId);

    if (coteInput) {
        coteInput.value = cote;
    }

    return cote;
};

/* =========================
   AJOUT AUTEUR
========================= */
window.addAuteur = function (prefix = '') {

    const inputId =
        prefix === 'edit'
            ? 'editNewAuteur'
            : 'newAuteur';

    const selectId =
        prefix === 'edit'
            ? 'editAuteurs'
            : 'auteurs';

    const input = document.getElementById(inputId);
    const select = document.getElementById(selectId);

    const nom = input?.value?.trim();

    if (!nom) return;

    fetch('/bibliothecaire/auteurs', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ nom })
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === 'exists') {

            showToast(
                "Cet auteur existe déjà dans votre liste",
                "warning"
            );

            const optionExists = Array.from(select.options)
                .find(opt => opt.value == data.auteur.id);

            if (optionExists) {
                optionExists.selected = true;
            }

            input.value = '';

            if (prefix === 'edit') {
                generateCote('edit');
            } else {
                generateCote();
            }

            return;
        }

        const option = document.createElement('option');

        option.value = data.auteur.id;
        option.textContent = data.auteur.nom;
        option.selected = true;

        select.appendChild(option);

        input.value = '';

        showToast(
            "Auteur ajouté avec succès",
            "success"
        );

        if (prefix === 'edit') {
            generateCote('edit');
        } else {
            generateCote();
        }
    });
};

window.showToast = function (message, type = 'primary') {

    const toastEl = document.getElementById('appToast');
    const toastBody = document.getElementById('toastMessage');

    toastBody.innerText = message;

    toastEl.className = `toast align-items-center text-white bg-${type} border-0`;

    const toast = new bootstrap.Toast(toastEl, {
        delay: 3000
    });

    toast.show();
};

let selectedLivreId = null;

window.addExemplaire = function (id) {

    selectedLivreId = id;

    const modal = new bootstrap.Modal(
        document.getElementById('addExemplaireModal')
    );

    modal.show();
};

window.toggleMenu = function(element) {
    document
        .querySelectorAll('.dropdown-menu-book')
        .forEach(menu => {
            if (menu !== element.querySelector('.dropdown-menu-book')) {
                menu.classList.remove('show');
            }
        });

    element
        .querySelector('.dropdown-menu-book')
        .classList.toggle('show');
}

window.openEditModal = function (cote) {

    const livre = window.livresData.find(l => l.cote === cote);

    console.log("COTE REÇU:", cote);
    console.log("LIVRE TROUVÉ:", livre);

    if (!livre) {
        alert("Livre non trouvé !");
        return;
    }

    // ✅ REMPLIR LES CHAMPS DE BASE
    document.getElementById('editLivreId').value = livre.id;
    document.getElementById('editTitre').value = livre.titre || '';
    document.getElementById('editIsbn').value = livre.isbn?.substring(3) || '';
    document.getElementById('editDescription').value = livre.description || '';
    document.getElementById('editCote').value = livre.cote || '';

    // ✅ PARSER LA COTE (format: "800 000007 ROM FR 005")
    // ✅ RÉCUPÉRATION DES VALEURS
const sousCateg = livre.sous_categorie_dewey;
const type = livre.type_livre;
const langue = livre.langue;
const categorie = livre.categorie;

console.log("Sous-catégorie:", sousCateg);

document.getElementById('editCategorie').value = categorie;

// Charger les sous-catégories
loadSousCategories('edit');
console.log(
    "Catégorie sélectionnée:",
    document.getElementById('editCategorie').value
);

setTimeout(() => {

    document.getElementById('editSubCategorie').value = sousCateg;
    document.getElementById('editTypeCode').value = type;
    document.getElementById('editLangue').value = langue;

    console.log(
        "Options disponibles:",
        [...document.getElementById('editSubCategorie').options]
            .map(o => o.value)
    );

}, 200);

    // ✅ REMPLIR ET NETTOYER LES AUTEURS (SANS DOUBLONS)
    const auteursSelect = document.getElementById('editAuteurs');
    
    // Récupérer TOUS les auteurs uniques de tous les livres
 auteursSelect.innerHTML = '';

window.auteursData.forEach(auteur => {

    const option = document.createElement('option');

    option.value = auteur.id;
    option.textContent = auteur.nom;

    if (
        livre.auteurs &&
        livre.auteurs.find(a => a.id == auteur.id)
    ) {
        option.selected = true;
    }

    auteursSelect.appendChild(option);
});

    // ✅ OUVRIR LE MODAL
    new bootstrap.Modal(
        document.getElementById('editLivreModal')
    ).show();
};

let deleteUrl = null;

window.confirmDeleteExemplaire = function(url)
{
    deleteUrl = url;

    new bootstrap.Modal(
        document.getElementById('deleteExemplaireModal')
    ).show();
};

document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('confirmDeleteExemplaire');

    if (btn) {

        btn.addEventListener('click', function () {

            const form = document.getElementById('deleteExemplaireForm');

            form.action = deleteUrl;

            form.submit();
        });
    }

});
/* =========================
   INIT GLOBAL
========================= */

document.getElementById('confirmAddExemplaire')
    ?.addEventListener('click', function () {

        const form = document.getElementById('addExemplaireForm');

        form.action = `/bibliothecaire/livres/${selectedLivreId}/exemplaires`;

        form.submit();
    });

document.addEventListener('DOMContentLoaded', () => {

    const btn = document.getElementById('saveEditLivreBtn');

    if (!btn) return;

    btn.addEventListener('click', () => {

        const livreId = document.getElementById('editLivreId').value;

        const form = document.getElementById('editLivreForm');

        // 🔥 Important : définir l'action dynamiquement
        form.action = `/livres/${livreId}`;

        // 🔥 Envoyer le formulaire vers Laravel (PUT via @method('PUT'))
        form.submit();

    });

});

document.addEventListener('DOMContentLoaded', () => {

    initLivres();

    loadSousCategories();

    const fields = [
        '#subCategorie',
        '#typeCode',
        '#categorie',
        '#auteurs',
        '[name="langue"]'
    ];

    fields.forEach(sel => {
        document.querySelector(sel)?.addEventListener('change', generateCote);
    });
});