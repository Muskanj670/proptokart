document.addEventListener("DOMContentLoaded", function () {
    const formSections = document.querySelectorAll(".form-section");
    const sectionButtons = document.querySelectorAll(".section-btn");
  
    sectionButtons.forEach((button) => {
      button.addEventListener("click", function () {
        const section = button.getAttribute("data-section");
  
        formSections.forEach((sectionElement) => {
          if (sectionElement.classList.contains(section)) {
            sectionElement.classList.add("active");
          } else {
            sectionElement.classList.remove("active");
          }
        });
      });
    });
  });
  
  function opentab(tabName) {
    var typeContents = document.querySelectorAll(".type-content");
    typeContents.forEach(function (content) {
      content.classList.remove("active");
    });
  
    var selectedTab = document.getElementById(tabName);
    selectedTab.classList.add("active");
  
    var typeButtons = document.querySelectorAll(".section-btn");
    typeButtons.forEach(function (button) {
      button.classList.remove("active-item");
    });
  
    event.currentTarget.classList.add("active-item");
  }
  
  
  function showMedia1(userId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                document.getElementById("mediaContainer1").innerHTML = xhr.responseText;
                document.getElementById("loading1").style.display = "none";
            } else {
                document.getElementById("mediaContainer1").innerHTML = "Error loading media.";
                document.getElementById("loading1").style.display = "none";
            }
        }
    };
    xhr.open("GET", "media/media_sell.php?userId=" + userId, true);
    xhr.send();
    document.getElementById("loading1").style.display = "block";
  }
  
  // JavaScript code to automatically logout user when leaving the page
  window.addEventListener('beforeunload', function (e) {
  });
  
  function showMedia2(userId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                document.getElementById("mediaContainer2").innerHTML = xhr.responseText;
                document.getElementById("loading2").style.display = "none";
            } else {
                document.getElementById("mediaContainer2").innerHTML = "Error loading media.";
                document.getElementById("loading2").style.display = "none";
            }
        }
    };
    xhr.open("GET", "media/media_sell.php?userId=" + userId, true);
    xhr.send();
    document.getElementById("loading2").style.display = "block";
  }
  
  // JavaScript code to automatically logout user when leaving the page
  window.addEventListener('beforeunload', function (e) {
  });
  
  function showMedia3(userId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                document.getElementById("mediaContainer3").innerHTML = xhr.responseText;
                document.getElementById("loading3").style.display = "none";
            } else {
                document.getElementById("mediaContainer3").innerHTML = "Error loading media.";
                document.getElementById("loading3").style.display = "none";
            }
        }
    };
    xhr.open("GET", "media/media_employee_access_sell.php?userId=" + userId, true);
    xhr.send();
    document.getElementById("loading").style.display = "block";
  }
  
  function showMedia4(userId) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                document.getElementById("mediaContainer4").innerHTML = xhr.responseText;
                document.getElementById("loading4").style.display = "none";
            } else {
                document.getElementById("mediaContainer4").innerHTML = "Error loading media.";
                document.getElementById("loading4").style.display = "none";
            }
        }
    };
    xhr.open("GET", "media/media_employee_access_rent.php?userId=" + userId, true);
    xhr.send();
    document.getElementById("loading").style.display = "block";
  }
  