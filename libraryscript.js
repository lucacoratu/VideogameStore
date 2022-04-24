var iframe = document.getElementById("game-frame");

function gameButtonClicked(e){
    //Add the source of the iframe
    //console.log(e.innerText);
    iframe.style.visibility = 'visible';
    iframe.setAttribute('src', 'gamepage.php?Name=' + e.innerText);

    //Remove the navbar inside the iframe
    var elmnt = iframe.contentWindow.document.querySelector("#navbar");
    console.log(elmnt);
    //elmnt.style.display = 'none';
}