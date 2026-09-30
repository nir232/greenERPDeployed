
function openSection(sectionId) {
    const sections = document.querySelectorAll('.section');

    sections.forEach(section => {
        section.style.display = 'none';
    });

    const selected = document.getElementById(sectionId);

    if (selected) {
        selected.style.display = 'block';
    }
}

const click = document.getElementById("click");
const updates = document.getElementById("updatesFeed");

function hideForms() {
    updates.style.display = "none";
}

function openUpdates() {

    hideForms();

    click.addEventListener('click', (event) => {
        event.stopPropagation();
        updates.style.display = "block";
    });

    document.addEventListener('click', (event) => {

        if (!updates.contains(event.target) && event.target !== click) {
            updates.style.display = "none";
        }

    });

}