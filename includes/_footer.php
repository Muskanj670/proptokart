<?php include "config.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-RSVDBB94BF"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-RSVDBB94BF');
  </script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../proptokart_css/footer.css" />
</head>


<div class="upper-footer">

  <div class="brand col-s-12 col-m-4 col-l-4">
    <img src="<?php echo BASE_URL; ?>images/proptokart.png" alt="Proptokart" style="width: 70px" />
    <p>
      Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse
      molestie consequat, vel illum dolore
    </p>
    <a href="#">Read More <i class="fa-solid fa-arrow-right"></i></a>
  </div>

  <div class="contact col-s-12 col-m-4 col-l-4">
    <h3>Contact Us</h3>
    <div class="links">
      <a href="https://www.google.com/maps/place/AcculizeinTech+-+Ethical+Hacking+Institute+In+Agra+%2F+Digital+Marketing+%2F+Graphics+Designing/@27.2101232,77.9728089,17z/data=!3m1!4b1!4m6!3m5!1s0x3974775995746b5d:0x12a7f28958f443f!8m2!3d27.2101232!4d77.9728089!16s%2Fg%2F11kjf90k10?entry=ttu"><i class="fa-solid fa-location-dot"></i>
        <span>Raj Deep Bhawan,Transport Colony, Agra 282007</span></a>
      <a href="#" onclick="callNumber('+917248605408')"><i class="fa-solid fa-phone"></i>
        <span>Call us FREE +91 724 860 5408</span></a>
      <a href="mailto:proptokart@gmail.com"><i class="fa-regular fa-envelope"></i><span>proptokart@gmail.com</span></a>
    </div>
    <a href="#">Contact Us <i class="fa-solid fa-arrow-right"></i></a>
  </div>

  <div class="social col-s-12 col-m-4 col-l-4">
    <h3>Newsletter Subscribe</h3>
    <form action="" style=' display: flex; flex-wrap: wrap; gap: 6px; align-items: center; text-align: center; margin-top: 3%;'>
      <input type="text" placeholder="Enter Your mail">
      <button type="submit">Submit</button>
    </form>

    <p>Don’t forget to follow us on:</p>
    <div class="social-links">
      <a href="https://www.facebook.com/people/proptokart/100086011914020/?mibextid=ZbWKwL"><i class="fa-brands fa-facebook-f"></i></a>
      <a href="https://www.instagram.com/proptokart/?hl=en"><i class="fa-brands fa-instagram"></i></a>
      <a href="https://www.linkedin.com/in/gaurav-mudgal-091a27212/?originalSubdomain=in"><i class="fa-brands fa-linkedin-in"></i></a>
      <a href="https://www.youtube.com/channel/UC3wLL1czpFgbQSwKqQjznSg"><i class="fa-brands fa-square-youtube"></i></a>
    </div>
  </div>
</div>
<div class="lower-footer">
  <p>© 2017 Proptokart, All Rights Reserved</p>
</div>
<script>
  function callNumber(phoneNumber) {
    window.location.href = "tel:" + phoneNumber;
  }
</script>

</html>