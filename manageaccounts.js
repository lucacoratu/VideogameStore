function checkboxclicked(e){

    //Get all the rows in the table
    const rows = [...document.querySelectorAll('#row')]; 
    //Get all the checkboxes in the table
    const checkboxes = [...document.querySelectorAll('#checkbox')];

    //console.log(rows);
    //console.log(checkboxes);
    //Get the username of the checkbox that was clicked
    let index = -1;
    for(let i =0; i < checkboxes.length; i++){
        if(checkboxes[i] === e){
            index = i;
            break;
        }
    }

    if(e.checked === true){
        //console.log(e);
        //Change the account type of the selected account to 2
        //console.log(row[index].children[0].innerText);
        $.ajax({
            type: 'POST',
            url: 'changeaccounttype.php',
            data: {modify: row[index].children[0].innerText, modifyType: 2},
            success: function(response){
                //Alert the user that the account has been updated
                alert(response);

                //Reload the page
                location.reload();
            },
            error: function(xhr, status, error){
                //Log the error
                console.error(xhr);
            }
        });
    }
    else{
        //Change the account type of the selected account to 1
        $.ajax({
            type: 'POST',
            url: 'changeaccounttype.php',
            data: {modify: row[index].children[0].innerText, modifyType: 1},
            success: function(response){
                //Alert the user that the account has been updated
                alert(response);

                //Reload the page
                location.reload();
            },
            error: function(xhr, status, error){
                //Log the error
                console.error(xhr);
            }
        });
    }
}