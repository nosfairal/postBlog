function approuveUser(){
    let xmlhttp = new XMLHttpRequest;
    xmlhttp.open("GET", "https://localhost/blogpost/index.php?p=admin/approuveUser/"+this.dataset.id);
    xmlhttp.send();
}

function upgradeUser(){
    let xmlhttp2 = new XMLHttpRequest;
    xmlhttp2.open("GET", "https://localhost/blogpost/index.php?p=admin/upgradeUser/"+this.dataset.id);
    xmlhttp2.send();
}

function init1(){
    let buttonsUsers = document.querySelectorAll(".form-check-input");
    for(let buttonsUser of buttonsUsers){
        buttonsUser.addEventListener("click", approuveUser);
    }
}

function init2(){    
    let upgradeButtons = document.querySelectorAll(".btn-success");
    for(let  upgradeButton of upgradeButtons){
        upgradeButton.addEventListener("click", upgradeUser);
    }
}

window.onload = () => {init1();init2();};






