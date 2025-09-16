
 // JS pour la sélection dynamique, calcul du total en direct et impression facture

document.addEventListener('DOMContentLoaded', function() {
    // Ajout dynamique de lignes de produits
    const addBtn = document.getElementById('add-product-row');
    const tableBody = document.getElementById('products-table-body');
    const produitsData = window.produitsData || [];

    if (addBtn && tableBody) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const row = createProductRow(produitsData);
            tableBody.appendChild(row);
            updateTotal();
        });
    }

    // Calcul du total en direct
    tableBody && tableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantite-input') || e.target.classList.contains('produit-select')) {
            updateTotal();
        }
    });

    // Impression facture
    const printBtn = document.getElementById('print-invoice');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }

    function createProductRow(produits) {
        const tr = document.createElement('tr');
        // Sélecteur produit
        const tdProduit = document.createElement('td');
        const select = document.createElement('select');
        select.name = 'produit[]';
        select.className = 'form-select produit-select';
        produits.forEach(p => {
            const option = document.createElement('option');
            option.value = p.id_produit;
            option.textContent = p.nom_produit;
            option.dataset.prix = p.prix_vente;
            select.appendChild(option);
        });
        tdProduit.appendChild(select);
        tr.appendChild(tdProduit);
        // Prix
        const tdPrix = document.createElement('td');
        tdPrix.className = 'prix-vente';
        tdPrix.textContent = produits[0] ? produits[0].prix_vente + ' FC' : '';
        tr.appendChild(tdPrix);
        // Quantité
        const tdQte = document.createElement('td');
        const inputQte = document.createElement('input');
        inputQte.type = 'number';
        inputQte.name = 'quantite[]';
        inputQte.min = 0;
        inputQte.value = 0;
        inputQte.className = 'form-control quantite-input';
        tdQte.appendChild(inputQte);
        tr.appendChild(tdQte);
        // Bouton supprimer
        const tdDel = document.createElement('td');
        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.className = 'btn btn-danger btn-sm remove-row';
        delBtn.innerHTML = 'Supprimer' ;
        delBtn.onclick = function() { tr.remove(); updateTotal(); };
        tdDel.appendChild(delBtn);
        tr.appendChild(tdDel);
        // Changement de produit => maj prix
        select.onchange = function() {
            const prix = select.options[select.selectedIndex].dataset.prix;
            tdPrix.textContent = prix + ' FC';
            updateTotal();
        };
        inputQte.oninput = updateTotal;
        return tr;
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('#products-table-body tr').forEach(tr => {
            const select = tr.querySelector('.produit-select');
            const qte = tr.querySelector('.quantite-input');
            if (select && qte) {
                const prix = parseFloat(select.options[select.selectedIndex].dataset.prix);
                const quantite = parseInt(qte.value) || 0;
                total += prix * quantite;
            }
        });
        document.getElementById('total-vente').textContent = total.toFixed(2) + ' FC';
    }
});
