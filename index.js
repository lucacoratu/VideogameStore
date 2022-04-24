window.addEventListener('load', (e)  =>{
    if(games.getBoundingClientRect().bottom < html_page.getBoundingClientRect().bottom) {
        //console.log("ceva");
        //Set the footer to the bottom
        footer_div.style.position = 'fixed';
        footer_div.style.top = (html_page.getBoundingClientRect().bottom - (footer_div.getBoundingClientRect().bottom - footer_div.getBoundingClientRect().top)) + 'px';
        footer_div.style.left = '0px';
    }
    else{
        //console.log("altceva");
        footer_div.style.position = 'fixed';
        footer_div.style.top = games.getBoundingClientRect().bottom + 'px';
    }
})

search_text.addEventListener("input", (e) => {
    const value = e.target.value;
    //console.log(value);

    if(value){
        
        let game_cards = games.children;
        //console.log(game_cards);
        
        for(let i =0; i < game_cards.length; i++){
            //Extract the game name
            let game_card_kids = game_cards[i].children[0].children[2];
            //console.log(game_card_kids);
            const isVisible = game_cards[i].children[0].children[2].textContent.toLowerCase().includes(value.toLowerCase());
            //console.log(isVisible);
            if(isVisible === false) {
                game_cards[i].style.display = "none";
            }
            else{
                game_cards[i].style.display = "flex";
            }
        }

        //Set the footer position to be absolute
        footer_div.style.visibility = "hidden";
    }
    else{
        //If the value is empty show all
        //console.log("empty");
        let game_cards = games.children;
        for(let i =0; i < game_cards.length; i++){
            game_cards[i].style.display = "flex";
        }
        footer_div.style.visibility = "";
        return;
    }
})

function getOffset( el ) {
    var _x = 0;
    var _y = 0;
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return { top: _y, left: _x };
}


//console.log(x);

window.addEventListener('resize', (e) => {
    if(games.getBoundingClientRect().bottom < html_page.getBoundingClientRect().bottom) {
        //console.log("ceva");
        //Set the footer to the bottom
        footer_div.style.position = 'fixed';
        footer_div.style.top = (html_page.getBoundingClientRect().bottom - (footer_div.getBoundingClientRect().bottom - footer_div.getBoundingClientRect().top)) + 'px';
        footer_div.style.left = '0px';
    }
    else{
       // console.log("altceva");
        footer_div.style.position = 'fixed';
        footer_div.style.top = games.getBoundingClientRect().bottom + 'px';
    }
})