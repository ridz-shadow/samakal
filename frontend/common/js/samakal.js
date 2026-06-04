$(document).ready(function () {
  //ShowHide in Mobile
  $(".AMobileHide").click(function () {
    $(".MobileShowHide").slideToggle();
  });

  //BackToTop
  $(document).ready(function () {
    $(window).scroll(function () {
      if ($(this).scrollTop() > 50) {
        $("#back_to_top").fadeIn();
      } else {
        $("#back_to_top").fadeOut();
      }
    });
    // scroll body to 0px on click
    $("#back_to_top").click(function () {
      $("#back-to-top").tooltip("hide");
      $("body,html").animate(
        {
          scrollTop: 0,
        },
        800
      );
      return false;
    });

    $("#back_to_top").tooltip("show");
  });
});

//Sticky

window.onscroll = function () {
  myFunction();
};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

var header2 = document.getElementById("myHeader2");
var sticky2 = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
  if (window.pageYOffset > sticky2) {
    header2.classList.add("sticky2");
  } else {
    header2.classList.remove("sticky2");
  }
}

//Search
$(".menu-search").click(function (event) {
  event.preventDefault();
  $(".search_block").toggle("show hide");
});
$("a.close-search").click(function (e) {
  e.preventDefault();
  $(".search_block").toggle("show hide");
});

$(".menu-left").click(function (e) {
  e.preventDefault();
  $(".MobileMenu, .menu-left i").toggleClass("show hide");
  $("body, .most_last_news_details, .most_read_details").toggleClass(
    "no-scrollbar"
  );
});

//Mobile Menu
$(document).ready(function () {
  //append plus symbol to every list item that has children
  $("#mobile-nav .parent").append(
    '<i class="open-menu material-icons">add</i>'
  );

  //fix non-scrolling overflow issue on mobile devices
  $("#mobile-nav>ul").wrap('<div class="overflow"></div>');
  $(window).on("load resize", function () {
    var vph = $(window).height() - 57; // 57px - height of #mobile-nav
    $(".overflow").css("max-height", vph);
  });

  // global variables
  var menu = $(".overflow>ul");
  var bg = $("html, body");

  function bgScrolling() {
    if (menu.hasClass("open")) {
      bg.css({
        "overflow-y": "hidden",
        height: "auto",
      });
    } else {
      bg.css({
        "overflow-y": "visible",
        height: "100%",
      });
    }
  }

  $(".menu-button").on("click", function (e) {
    e.preventDefault();
    //activate toggles
    menu.slideToggle(250);
    menu.toggleClass("open");
    $(this).children().toggleClass("fa-reorder fa-remove");
    bgScrolling();
  });

  //list item click events
  $(".open-menu").on("click", function (e) {
    e.preventDefault();
    $(this).prev("ul").slideToggle(250);
    $(this).toggleClass("rotate");
  });
});

//MenuBtn
function myMenuBtnChng() {
  var element = document.getElementById("menu-button");
  element.classList.toggle("fa-times");
  element.classList.toggle("fa-bars");
}
