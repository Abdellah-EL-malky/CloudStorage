function showShareModal(type, id) {
    currentShareableType = type;
    currentShareableId = id;

    document.getElementById('shareForm').reset();
    document.getElementById('shareUrlContainer').style.display = 'none';

    document.getElementById('shareModalTitle').textContent = 'Partager ' + (type === 'folder' ? 'un dossier' : 'un fichier');

    const route = type === 'folder' ? '/shares/folder/' + id : '/shares/file/' + id;
    document.getElementById('shareForm').action = route;

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

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('avatar')) {
        setupAvatarPreview();
    }

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
