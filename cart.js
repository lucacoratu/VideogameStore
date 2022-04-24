function removeAllItemsFromCart(){
    $.ajax({
        type: 'POST',
        url: 'clearcart.php',
        data: {clear: 'all'},
        success: function(response){
            //Reload the page
            location.reload();
            alert(response);
        },
        error: function(xhr, status, error){
            //Log the error
            console.error(xhr);
        }
    });
}


function removeItemFromCart(item){
    const cartItems = [...document.querySelectorAll('#cart-item')];
    //console.log(cartItems);

    //Find in witch cart-item is the clicked item
    cartItems.forEach(element => {
        if(element.children[2].children[1].children[0] === item){
            //Send a request to remove this item from the cart
            let gameName = element.children[1].children[0].innerText;
            $.ajax({
                type: 'POST',
                url: 'removeItem.php',
                data: {gamename: gameName},
                success: function(response){
                    //Alert the user that the item has been removed
                    alert(response);

                    //Reload the page
                    location.reload();
                },
                error: function(xhr, status, error){
                    //Log the error
                    console.error(xhr);
                }
            })

            return;
        }
    });
}

function checkoutCart(){
    //Take all of the items in the cart
    const cartItems = [...document.querySelectorAll('#cart-item')];

    //Add all of the game names into an array
    let gameNames = [];
    cartItems.forEach(element => {
        gameNames.push(element.children[1].children[0].innerText);
    })

    //console.log(gameNames);
    $.ajax({
        type: 'POST',
        url: 'purchase.php',
        data: {gamename: gameNames},
        success: function(response){
            //Alert the user that the item has been removed
            alert(response);

            //Reload the page
            location.reload();
        },
        error: function(xhr, status, error){
            //Log the error
            console.error(xhr);
        }
    })
}