window.addEventListener('close-modal', (event) => {

    // The id of the modal is passed from component
    const modalId = event.detail;
    if (modalId == null) return;

    //find modal
    const modal = document.getElementById(modalId);

    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    modal.setAttribute('style', 'display: none');
    // get modal backdrops
    const modalsBackdrops = document.getElementsByClassName('modal-backdrop');
    document.body.removeChild(modalsBackdrops[0]);
});
