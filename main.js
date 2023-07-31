"use strict"

// ========header_logout_btn========
document.addEventListener("DOMContentLoaded", function() {
    // =========ЭТО ФОРМЫ=========
    function add_answer (button){
        let el_ans = button.parentNode.lastElementChild;

        let input_ans = document.createElement("input");
        input_ans.setAttribute("type","text");
        input_ans.setAttribute("class", "test");
        
        el_ans.appendChild(input_ans, button);
    }

    function remove_answer (button){
        let el_ans = button.parentNode.lastElementChild;
        let InputsArr = el_ans.querySelectorAll('.test')
        InputsArr[InputsArr.length - 1].remove();
    }
        

    document.body.addEventListener('click', function(e){
        let node = e.target;
        if(node.classList.contains('add')){
            add_answer(node);
        }
        else if(node.classList.contains('remove')){
            remove_answer(node);
        }
        
    
    }, true)

    document.addEventListener('scroll', function(){
        if(window.scrollY > 300){
            document.querySelector('.card_cover_third .card').style = 'animation: card_open1 .8s 0s forwards ease-in-out;';
            document.querySelector('.card_cover_fourth .card').style = 'animation: card_open1 .8s 0s forwards ease-in-out;';
            document.querySelector('.card_cover_third .card .circle').style.setProperty('--sq-animation', 'card_open2  .8s 0s forwards ease-in-out');
            document.querySelector('.card_cover_fourth .card .circle').style.setProperty('--sq-animation', 'card_open2  .8s 0s forwards ease-in-out');
            document.querySelector('.card_cover_third .card .content').style = 'animation: card_open3 .4s .5s forwards ease-in-out;';
            document.querySelector('.card_cover_fourth .card .content').style = 'animation: card_open6 .4s .5s forwards ease-in-out;';
            document.querySelector('.card_cover_third .card .logo').style = 'animation: card_open4 .4s .6s forwards ease-in-out;';
            document.querySelector('.card_cover_fourth .card .logo').style = 'animation: card_open5 .4s .6s forwards ease-in-out;';
        }
    })

    // =========Logout btn==========
    let width_of_user_name = this.querySelector('header .user_name').getBoundingClientRect().width;
    this.querySelector('header .header_logout_btn').style.width = width_of_user_name + 'px';
});