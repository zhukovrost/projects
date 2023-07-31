
// Popup window to leave feedback
const feedbackBtn = document.querySelector('.reports .links button');
const popupWindow = document.querySelector('.popup_feedback');

feedbackBtn.addEventListener('click', function(){
    popupWindow.classList.add("open");
});

const closeBtn = document.querySelector('.popup_feedback .close');
closeBtn.addEventListener('click', function(){
    popupWindow.classList.remove("open");
});

window.addEventListener('keydown', (e) => {
if(e.key == "Escape"){
    popupWindow.classList.remove("open");
}
});

document.querySelector('.popup_feedback form').addEventListener('click', event => {
    event.isClickWithInModal = true;
});

popupWindow.addEventListener('click', event =>{
if(event.isClickWithInModal) return;
    event.currentTarget.classList.remove('open');
});
