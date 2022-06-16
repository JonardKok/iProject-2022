const rubricNumberIndex = 0;
const rubricNameIndex = 1;
const rubricParentIndex = 2;


//Algemene functie voor het maken van rubrieken
function makeRubrics() {
    makeParents(); //Maakt eerst parents die aan de de categorie "alle categorieën" zitten
    makeSkippedRubrics(getNoneParentRubrics()); //Maakt de rest van children
}


//Loopt door de lijst heen en maakt een dropdown-item aan die zichzelf toevoegt aan zijn parent.
//Als de parent nog niet in de lijst voorbij is gekomen, voegt hij het toe aan de array leftOverRubrics.
//Met deze array roept de functie zichzelf weer aan en doet hij hetzelfde, net zolang tot er geen children meer over zijn.
function makeSkippedRubrics(rubrics) {
    let leftOverRubrics = [];
    for (var index = 0; index < rubrics.length; index++) {
        let rubric = rubrics[index];

        let rubricNumber = rubric[rubricNumberIndex];
        let rubricName = rubric[rubricNameIndex];
        let rubricParent = rubric[rubricParentIndex];

        dropdownItem = makeDropdownItem(rubricName, rubricNumber);

        let parentElement = document.getElementById(rubricParent);
        
        if (parentElement != null) {
            parentElement.appendChild(dropdownItem);
        } else {
            leftOverRubrics.push(rubric);
        }
    }
    if(leftOverRubrics.length != 0){
        makeSkippedRubrics(leftOverRubrics);
    }
}


//Returnt een array zonder de root en parents
function getNoneParentRubrics(){
    let noneParentRubrics = [];
    for (var index = 1; index < rubricArray.length; index++) {
        let rubric = rubricArray[index];
        if(rubric[rubricParentIndex] != null && rubric[rubricParentIndex] != -1){
            noneParentRubrics.push(rubric);
        }
    }
    return noneParentRubrics;
}


//Returnt een dropdown-item
function makeDropdownItem(name, id) {
    let dropdownItemLi = document.createElement("li");
    let dropdownItemUl = document.createElement("ul");

    dropdownItemUl.id = id;

    if (isRubricEmpty(id)) {
        dropdownItemUl.classList.add("w-100");
        dropdownItemLi.innerHTML = "<a class='dropdown-item text-wrap text-break' href='categoriepagina.php?categorie=" + id + "'>" + name + "</a>";
    } else {
        dropdownItemLi.classList.add("dropdown-submenu");
        dropdownItemUl.classList.add("dropdown-menu", "w-100");
        dropdownItemLi.innerHTML = "<div class='dropdown-item dropdown-toggle'><a class='text-wrap text-decoration-none link-dark text-break' href='categoriepagina.php?categorie=" + id + "'>" + name + "</a></div>";
    }

    dropdownItemLi.appendChild(dropdownItemUl);

    return dropdownItemLi;
}


//Checkt of de rubriek een uitklap menu wordt of niet.
function isRubricEmpty(id) {
    for (var index = 0; index < rubricArray.length; index++) {
        let rubric = rubricArray[index];
        if (rubric[rubricParentIndex] === id) {
            return false;
        }
    }
    return true;
}

//Voegt de parents toe aan het algemene menu element.
function makeParents() {
    let mainElement = document.getElementById("rubricsTree");
    for (var index = 1; index < rubricArray.length; index++) {
        let rubric = rubricArray[index];

        let rubricNumber = rubric[rubricNumberIndex];
        let rubricName = rubric[rubricNameIndex];
        let rubricParent = rubric[rubricParentIndex];

        if (rubricParent == -1) {
            dropdownItem = makeDropdownItem(rubricName, rubricNumber);
            mainElement.appendChild(dropdownItem);
        }
    }
}
