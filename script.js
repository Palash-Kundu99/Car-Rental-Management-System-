$(document).ready(function () {
  var silder = $(".owl-carousel");
  silder.owlCarousel({
    autoPlay: true, // Enables auto-play
    autoplayTimeout: 3000, // Sets the auto-scroll speed (in milliseconds)
    items: 1,
    center: false,
    nav: true,
    margin: 40,
    dots: false,
    loop: true, // Enables infinite loop
    navText: [
      "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
      "<i class='fa fa-arrow-right' aria-hidden='true'></i>",
    ],
    responsive: {
      0: {
        items: 1,
      },
      575: { items: 1 },
      768: { items: 2 },
      991: { items: 3 },
      1200: { items: 4 },
    },
  });
});
$(document).ready(function () {
  function updateHoriSelector() {
    var $active = $("#navbarSupportedContent .nav-item.active");
    if ($active.length) {
      var activeWidth = $active.innerWidth();
      var activeHeight = $active.innerHeight();
      var itemPosTop = $active.position().top;
      var itemPosLeft = $active.position().left;
      $(".hori-selector").css({
        top: itemPosTop + "px",
        left: itemPosLeft + "px",
        width: activeWidth + "px",
        height: activeHeight + "px",
      });
    }
  }

  $("#navbarSupportedContent").on("click", ".nav-item", function () {
    $("#navbarSupportedContent .nav-item").removeClass("active");
    $(this).addClass("active");
    updateHoriSelector();
  });

  $(window).on("resize", function () {
    setTimeout(updateHoriSelector, 300);
  });

  $(".navbar-toggler").click(function () {
    $(".navbar-collapse").slideToggle(300);
    setTimeout(updateHoriSelector, 300);
  });

  updateHoriSelector();
});
