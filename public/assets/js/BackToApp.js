function GoToSite() {
    location.href = "https://artintoner.com";
}

function load() {
    const body = document.body;
    const elementsToLoad = body.querySelectorAll('i, h3, button, p');

    elementsToLoad.forEach(element => {
        element.classList.add('load');
    });
}

