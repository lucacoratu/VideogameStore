
function imageClicked(e) {
    //Get the array of images
    list_images = document.getElementById('list-images').children;

    for(let i =0; i < list_images.length; i++){
        if(list_images[i].getAttribute("class") != null){
            list_images[i].removeAttribute("class");
            break;
        }
    }

    //console.log(e);
    //console.log(list_images);
    let pos = 0;
    for(let i=0; i < list_images.length; i++)
    {
        if(e.srcElement == list_images[i]){
            pos = i;
            break;
        }
    }

    //console.log(pos);
    video_shower.pause();
    video_shower.currentTime = 0;

    //console.log(list_images[pos]);

    let children = list_images[pos].children;
    //console.log(children);

    if(children.length === 0){
        video_shower.setAttribute("hidden",true);
        image_shower.removeAttribute("hidden",true);
        let source_image = list_images[pos].getAttribute("src");
        //console.log(source_image);
        image_shower.setAttribute("src", source_image);
        list_images[pos].setAttribute("class", "selected-image");
    }
    else{
        video_shower.removeAttribute("hidden", true);
        image_shower.setAttribute("hidden", true);
        let source_image = list_images[pos].children[0].getAttribute("src");
        console.log(source_image);
        //video_shower.innerHtml = "<source src=\"" + source_image + "\" type=\"video/webm\">";
        video_shower.src =  source_image;
        //console.log(video_shower.innerHtml);
        list_images[pos].setAttribute("class", "selected-image");
    }
}

function setupVideos() {
    for (const video of document.querySelectorAll('#video_shower')) {
      video.controls = false
      video.addEventListener('mouseover', () => { video.controls = 'controls' })
      video.addEventListener('mouseout', () => { video.controls = false })
    }

    //Setup the stars for the reviews
    for(let i = 0; i < 10; i++){
        const el = document.getElementById('review_star' + i);
        //Add the event listeners
        el.addEventListener('mouseover', starHovered);
        el.addEventListener('mouseout', starMouseOut);
        el.addEventListener('click', starClicked);
    }
  }
window.addEventListener('load', setupVideos, false)

function addGameToCart(e){
    //Take the game name from the title
    let gameName = document.getElementById('game_name');
    //Send the game name to be added in the cart
    //console.log(gameName.innerText);
    $.ajax({
        type: 'POST',
        url: 'addtocart.php',
        data: {gamename: gameName.innerText},
        success: function(response){
            alert(response);
        },
        error: function(xhr, status, error){
            console.error(xhr);
        }
    })
}

function starHovered(e){
    //Color of the stars from behind
    const src = e.srcElement;
    //console.log(src);
    const srcId = src.getAttribute('id');
    //Get the number of the star
    starNumber = srcId.substring(srcId.length - 1);
    //console.log(starNumber);

    for(let i = 0; i <= starNumber; i++){
        const el = document.getElementById('review_star' + i);
        el.style.color = 'orange';
    }

    for(let i = 0; i <= starClickedNumber; i++){
        const el = document.getElementById('review_star' + i);
        el.style.color = 'orange';
    }
}

function starMouseOut(e){
    const src = e.srcElement;
    //console.log(src);
    const srcId = src.getAttribute('id');
    //Get the number of the star
    starNumber = srcId.substring(srcId.length - 1);
    //console.log(starNumber);

    for(let i = 0; i <= starNumber; i++){
        const el = document.getElementById('review_star' + i);
        el.style.color = 'white';
    }

    for(let i = 0; i <= starClickedNumber; i++){
        const el = document.getElementById('review_star' + i);
        el.style.color = 'orange';
    }
}

let starClickedNumber = -1;
function starClicked(e){
    //When a star is clicked the 
    const src = e.srcElement;
    const srcId = src.getAttribute('id');
    //Get the number of the star
    starNumber = srcId.substring(srcId.length - 1);

    const prevClickedStar = starClickedNumber;
    starClickedNumber = starNumber;
    //console.log(starClickedNumber);

    //If the star clicked is lower than the preview star clicked then
    //Clear the start after the clicked one

    if(prevClickedStar > starClickedNumber){
        //console.log(starClickedNumber + 1);
        let start = parseInt(starClickedNumber);
        start = start + 1;
        for(let i = start; i <= prevClickedStar && i < 10; i++) {
            console.log(i);
            const el = document.getElementById('review_star' + i);
            el.style.color = 'white';
        }
    }

    for(let i = 0; i <= starClickedNumber; i++){
        const el = document.getElementById('review_star' + i);
        el.style.color = 'orange';
    }
}

function reviewGame(){
    //The grade that the user has given is the starClickedNumber + 1 (index from 0)
    let gradeGiven = parseInt(starClickedNumber);
    gradeGiven = gradeGiven + 1;

    let gameName = document.getElementById('game_name').innerText;
    //console.log(gameName);

    //Send the grade to the server as POST request
    $.ajax({
        type: 'POST',
        url: 'rategame.php',
        data: {gamename: gameName, grade: gradeGiven},
        success: function(response){
            alert(response);
            //Reload the page
            location.reload();
        },
        error: function(xhr, status, error){
            console.error(xhr);
        }
    });
}