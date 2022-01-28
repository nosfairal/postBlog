window.onload = () =>{
    let buttonsUsers = document.querySelectorAll(".form-check-input")
    for(let buttonsUser of buttonsUsers){
        buttonsUser.addEventListener("click", approuveUser)
    }
}
function approuveUser(){
    let xmlhttp = new XMLHttpRequest;
    xmlhttp.open('GET', 'https://localhost/blogpost/index.php?p=admin/approuveUser/'+this.dataset.id)
    xmlhttp.send()
}