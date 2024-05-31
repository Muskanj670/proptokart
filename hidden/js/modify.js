document.addEventListener('DOMContentLoaded', function () {
    const formSections = document.querySelectorAll('.form-section');
    const sectionButtons = document.querySelectorAll('.section-btn');

    sectionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const section = button.getAttribute('data-section');

            formSections.forEach(sectionElement => {
                if (sectionElement.classList.contains(section)) {
                    sectionElement.classList.add('active');
                } else {
                    sectionElement.classList.remove('active');
                }
            });
        });
    });
});


function togglePropertyType() {
    const residentialSection = document.getElementById('residentialSection');
    const commercialSection = document.getElementById('commercialSection');
    const propertyType = document.querySelector('input[name="propertyType"]:checked').value;

    if (propertyType === 'Residential') {
        residentialSection.classList.remove('hidden');
        commercialSection.classList.add('hidden');
    } else if (propertyType === 'Commercial') {
        residentialSection.classList.add('hidden');
        commercialSection.classList.remove('hidden');
    } else {
        residentialSection.classList.add('hidden');
        commercialSection.classList.add('hidden');
    }
}