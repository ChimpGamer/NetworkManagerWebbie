window.addEventListener('close-modal', (event) => {

    // The id of the modal is passed from component
    const modalId = event.detail;
    if (modalId == null) return;

    //find modal
    const modal = document.getElementById(modalId);
    const mdbModal = window.mdb.Modal.getInstance(modal);
    mdbModal.hide();
});
