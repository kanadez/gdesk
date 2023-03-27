/**
 * Открывает окно по modal_id
 *
 * @param modal_id
 */
function openModal(modal_id){
    $(`#${modal_id}`).toggleClass('_active')
                     .closest('.modal-backdrop').fadeIn()
                     .closest('body').addClass('_modal-open');
}

/**
 * Закрывает окно в случае, если открыты два и более окон
 *
 * @param modal_id
 */
function closeModal(modal_id){
    $(`#${modal_id}`)
        .removeClass('_active')
        .closest('.modal-backdrop').fadeOut()

    if ($('._active').length === 0) {
        $(`#${modal_id}`).closest('body').removeClass('_modal-open');
    }

    destroySliders();
}

/**
 * Закрывает все открытые модальные окна
 *
 */
function closeCurrentModal(){
    $('.modal').removeClass('_active');
    $('body').removeClass('_modal-open');
    $('.modal-backdrop').fadeOut();
}
