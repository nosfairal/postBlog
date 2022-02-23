function approve(){
    let xmlhttp = new XMLHttpRequest;
    xmlhttp.open("GET", "https://localhost/blogpost/index.php?p=admin/approveComment/"+this.dataset.id);
    xmlhttp.send();
}

window.onload = () => {
    let buttons = document.querySelectorAll(".form-check-input");
    for(let button of buttons){
        button.addEventListener("click", approve);
    }
};




