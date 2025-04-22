document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // MODALE SUPPRESSION UTILISATEUR
    const modalUser = document.getElementById('modalSuppUser');
    const btnsOpenUserModal = document.querySelectorAll('.btnSupprimerUtilisateur');
    const confirmBtnUser = document.getElementById('confirmSuppUser');
    const closeBtnUser = document.getElementById('btnCloseModalUser');

    if (modalUser && confirmBtnUser && closeBtnUser && btnsOpenUserModal.length) {
        let userId = null;

        btnsOpenUserModal.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                userId = btn.dataset.id;
                modalUser.style.display = 'flex';
            });
        });

        confirmBtnUser.addEventListener('click', () => {
            if (userId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'admin';

                const inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'action';
                inputAction.value = 'supprimerUtilisateur';

                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'id_utilisateur';
                inputId.value = userId;


                form.appendChild(inputAction);
                form.appendChild(inputId);
                document.body.appendChild(form);
                form.submit();
            }
        });

        closeBtnUser.addEventListener('click', () => {
            modalUser.style.display = 'none';
            userId = null;
        });
    }

    // MODALE SUPPRESSION COMMENTAIRE
    const modalCom = document.getElementById('modalSuppCom');
    const btnsOpenComModal = document.querySelectorAll('.btnSupprimerCommentaire');
    const confirmBtnCom = document.getElementById('confirmSuppCom');
    const closeBtnCom = document.getElementById('btnCloseModalCom');

    if (modalCom && confirmBtnCom && closeBtnCom && btnsOpenComModal.length) {
        let idCom = null;

        btnsOpenComModal.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                idCom = btn.dataset.id;
                modalCom.style.display = 'flex';
            });
        });

        confirmBtnCom.addEventListener('click', () => {
            if (idCom) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'admin';

                const inputAction = document.createElement('input');
                inputAction.type = 'hidden';
                inputAction.name = 'action';
                inputAction.value = 'supprimerCommentaire';

                const inputId = document.createElement('input');
                inputId.type = 'hidden';
                inputId.name = 'id_commentaire';
                inputId.value = idCom;

                form.appendChild(inputAction);
                form.appendChild(inputId);

                document.body.appendChild(form);
                form.submit();
            }
        });

        closeBtnCom.addEventListener('click', () => {
            modalCom.style.display = 'none';
            idCom = null;
        });
    }
});