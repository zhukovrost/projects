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


    // =========Logout btn==========
    let width_of_user_name = this.querySelector('header .user_name').getBoundingClientRect().width;
    this.querySelector('header .header_logout_btn').style.width = width_of_user_name + 'px';

    
});