

const selectMenus = document.querySelectorAll(".select-menu");


selectMenus.forEach((selectMenu) => {
    const select = selectMenu.querySelector(".select");
    const optionsList = selectMenu.querySelector(".options-list");
    const options = selectMenu.querySelectorAll(".option");

    // Show & hide options list
    // select.addEventListener("click", () => {
    //     optionsList.classList.toggle("active");
    //     select.querySelector(".fa-angle-down").classList.toggle("fa-angle-up");
    // });

    // Select option
    options.forEach((option) => {
        option.addEventListener("click", () => {
            options.forEach((option) => { option.classList.remove('selected') });
            select.querySelector("span").innerHTML = option.innerHTML;
            option.classList.add("selected");
            optionsList.classList.toggle("active");
            select.querySelector(".fa-angle-down").classList.toggle("fa-angle-up");
        });
    });
});




// --------------------------slider-----------------------------

var timeOut = 2000;
var slideIndex = 0;
var autoOn = true;

autoSlides();

function autoSlides() {
    timeOut = timeOut - 20;

    if (autoOn == true && timeOut < 0) {
        showSlides();
    }
    setTimeout(autoSlides, 20);
}

function prevSlide() {

    timeOut = 2000;

    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slideIndex--;

    if (slideIndex > slides.length) {
        slideIndex = 1
    }
    if (slideIndex == 0) {
        slideIndex = 2
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}

function showSlides() {

    timeOut = 2000;

    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slideIndex++;

    if (slideIndex > slides.length) {
        slideIndex = 1
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}


// $(document).ready(function () {
// // Show the select element and populate options when user types in the input
// $('#addressInput').on('input', function () {
// var inputText = $(this).val();
// $.ajax({
// url: '', // Path to the same PHP script
// type: 'GET',
// data: { input: inputText }, // Pass user input as parameter
// dataType: 'html',
// success: function (response) {
// console.log(response);
// $('#addressSelect').html(response); // Update select options with filtered data
// $('#addressSelect').show(); // Show select element
// },
// error: function (xhr, status, error) {
// console.error('Error fetching cities:', error);
// }
// });
// });

// // Hide the select box when user clicks outside of it
// $(document).on('click', function (event) {
// if (!$(event.target).closest('.select-menu').length) {
// $('#addressSelect').hide();
// }
// });
// });

$(document).ready(function () {
    $('#addressInput').on('input', function () {
        var inputText = $(this).val();
        $.ajax({
            url: 'index.php', // Use index.php as the URL
            type: 'GET',
            data: { input: inputText }, // Pass user input as parameter
            dataType: 'json',
            success: function (response) {
                // Clear previous options
                $('#addressSelect').html('');
                // Check if any addresses are returned
                if (response.length > 0) {
                    // Add new options based on the response
                    $.each(response, function (index, address) {
                        $('#addressSelect').append('<option value="' + address + '">' + address + '</option>');
                    });
                    $('#addressSelect').show(); // Show select element
                } else {
                    // If no addresses found, show a message
                    $('#addressSelect').html('<option value="">No matching addresses</option>');
                    $('#addressSelect').show(); // Show select element
                }
            },
            error: function (xhr, status, error) {
                try {
                    console.log('Error fetching addresses:');
                } catch (e) {
                    console.log('Error handling error:', e);
                }
            }
        });
    });
});
