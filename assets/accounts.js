
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