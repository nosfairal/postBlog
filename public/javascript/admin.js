window.onload = () => {
    let buttons = document.querySelectorAll(".form-check-input");
    for(let button of buttons){
        button.addEventListener("click", approuve);
    }
};

function approuve(){
    let xmlhttp = new XMLHttpRequest;
    xmlhttp.open('GET', "https://localhost/blogpost/index.php?p=admin/approuveComment/"+this.dataset.id);
    xmlhttp.send();
};


