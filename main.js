"use strict"
function add_answer (button){
    let el_ans = button.parentNode.lastElementChild;
    console.log(el_ans)

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


let InputsReg = document.body.querySelectorAll('main input');
let main = document.body.querySelector('main');

for(let i = 0; i < InputsReg.length; i++){
    InputsReg[i].oninput = function(){
        if(document.body.querySelector('#save_button') == null){
            let btn = document.createElement('button');
            btn.innerHTML = "Сохранить изменения";
            btn.id = "save_button";
            btn.type = "submit";
            main.appendChild(btn);
            document.body.querySelector('#save_button').addEventListener('click', function(){
                this.remove();
            })
        }
        
    };
}
