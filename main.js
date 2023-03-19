"use strict"
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


let InputsAcc = document.body.querySelectorAll('main input');
let form = document.body.querySelector('#user');
let InputsAccText = [];


for(let i = 0; i < InputsAcc.length; i++){
    InputsAccText.push(InputsAcc[i].value);
}

for(let i = 0; i < InputsAcc.length; i++){
    InputsAcc[i].oninput = function(){
        if(document.body.querySelector('#save_button') == null){
            let btn = document.createElement('button');
            btn.innerHTML = "<label><img style='height: 30px; margin-right: 5px;' src='img/icons/save.png'><p>Сохранить изменения</p></label>";
            btn.id = "save_button";
            btn.type = "submit";
            btn.style = `border: 2px solid black; height: 100px`
            form.appendChild(btn);

            document.body.querySelector('#save_button').addEventListener('click', function(){
                InputsAccText[i] = InputsAcc[i].value;
            })
        }
        
        for(let i = 0; i < InputsAcc.length; i++){
            InputsAccText.push(InputsAcc[i].value);
        }
        
        let flag = true;
        for(let i = 0; i < InputsAcc.length; i++){
            if(InputsAccText[i] != InputsAcc[i].value){
                flag = false;
            }
        }
        if(flag){
            document.body.querySelector('#save_button').remove();
        }
    };
}