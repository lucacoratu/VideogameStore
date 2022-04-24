let detailsNumber = 1;

const maxDetailsNumber = 6;

const detailsContainer = document.getElementById('details-container');

function addDetailsClicked(e){
    /* Add a new title and a new content in the form (max 6)*/

    if(detailsNumber === maxDetailsNumber)
        return;

    detailsNumber = detailsNumber + 1;

    //Create the title element
    titleElement = document.createElement('div');
    titleElement.setAttribute('class', 'title-element');

    label = document.createElement('label');
    label.innerText = "Title " + detailsNumber + ":";

    input = document.createElement('input');
    input.setAttribute('type', 'text');
    input.setAttribute('maxLength', '300');
    input.setAttribute('id', 'title' + detailsNumber);
    input.setAttribute('name', 'title' + detailsNumber);

    titleElement.append(label);
    titleElement.append(input);

    //console.log(titleElement);

    //Create the content-element
    contentElement = document.createElement('div');
    contentElement.setAttribute('class', 'content-element');
    
    labelContent = document.createElement('label');
    labelContent.innerText = `Content ${detailsNumber}:`;

    inputContent = document.createElement('input');
    inputContent.setAttribute('type', 'text');
    inputContent.setAttribute('maxLength', '2000');
    inputContent.setAttribute('id', 'content' + detailsNumber);
    inputContent.setAttribute('name', 'content' + detailsNumber);

    contentElement.append(labelContent);
    contentElement.append(inputContent);

    //console.log(contentElement);

    titleContentDiv = document.createElement('div');
    titleContentDiv.setAttribute('class', 'title-content');

    titleContentDiv.append(titleElement);
    titleContentDiv.append(contentElement);

    detailsContainer.append(titleContentDiv);
}

function removeDetailsClicked(e){
    //Removes the last detail added in the list (list should have at least 1 element)

    if(detailsNumber === 1)
        return;

    detailsContainer.removeChild(detailsContainer.lastElementChild);
    detailsNumber--;
}