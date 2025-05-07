// Fonctions pour les partages
function showShareModal(type, id) {
    // Définir les variables globales
    currentShareableType = type;
    currentShareableId = id;

    // Réinitialiser le formulaire
    document.getElementById('shareForm').reset();
    document.getElementById('shareUrlContainer').style.display = 'none';

    // Mettre à jour le titre du modal
    document.getElementById('shareModalTitle').textContent = 'Partager ' + (type === 'folder' ? 'un dossier' : 'un fichier');

    // Mettre à jour l'action du formulaire
    const route = type === 'folder' ? '/shares/folder/' + id : '/shares/file/' + id;
    document.getElementById('shareForm').action = route;

    // Ouvrir le modal
    var modal = new bootstrap.Modal(document.getElementById('shareModal'));
    modal.show();
}

function toggleEmailField() {
    const shareType = document.getElementById('share_type').value;
    const emailField = document.getElementById('emailFieldContainer');

    if (shareType === 'user') {
        emailField.style.display = 'block';
        document.getElementById('email').setAttribute('required', 'required');
    } else {
        emailField.style.display = 'none';
        document.getElementById('email').removeAttribute('required');
    }
}

function copyShareUrl() {
    const shareUrlInput = document.getElementById('shareUrl');
    shareUrlInput.select();
    document.execCommand('copy');
    alert('Lien copié dans le presse-papier');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Lien copié dans le presse-papier');
    }, function() {
        alert('Impossible de copier le lien');
    });
}

// Fonction pour la prévisualisation d'avatar
function setupAvatarPreview() {
    const avatarInput = document.getElementById('avatar');
    const avatarImage = document.querySelector('.img-thumbnail');

    if (avatarInput && avatarImage) {
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    avatarImage.src = e.target.result;
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    }
}

// Initialiser les fonctions lorsque le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Configurer la prévisualisation de l'avatar si on est sur la page de profil
    if (document.getElementById('avatar')) {
        setupAvatarPreview();
    }

    // Configurer le formulaire de partage s'il existe
    const shareForm = document.getElementById('shareForm');
    if (shareForm) {
        shareForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('shareUrl').value = data.share_url;
                        document.getElementById('shareUrlContainer').style.display = 'block';
                        document.getElementById('shareButton').textContent = 'Mettre à jour le partage';
                    } else {
                        alert('Une erreur est survenue lors du partage.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors du partage.');
                });
        });
    }
});
