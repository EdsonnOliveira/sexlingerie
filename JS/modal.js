function showModal(modalID) {
    var modal = document.getElementById(modalID);
    if (modal) {
        modal.classList.add('mdShow');
        modal.addEventListener('click', (e) => {
            if (e.target.classList.contains('closeModal')) {
                modal.classList.remove('mdShow');
            }
        });
    }
}