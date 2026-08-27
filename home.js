document.addEventListener("DOMContentLoaded", function () {
  // Destination slider
  const destinationSlider = document.getElementById("slider-destination");
  const destinationSlide = destinationSlider.querySelector(".slide");
  const destinationSlideWidth = destinationSlide.offsetWidth;

  document.getElementById("destination-next").addEventListener("click", () => {
    destinationSlider.scrollBy({ left: destinationSlideWidth, behavior: "smooth" });
  });

  document.getElementById("destination-prev").addEventListener("click", () => {
    destinationSlider.scrollBy({ left: -destinationSlideWidth, behavior: "smooth" });
  });

  // Hotel slider
  const hotelSlider = document.getElementById("hotel-slider");
  const hotelSlide = hotelSlider.querySelector(".slide");
  const hotelSlideWidth = hotelSlide.offsetWidth;

  document.getElementById("hotel-next").addEventListener("click", () => {
    hotelSlider.scrollBy({ left: hotelSlideWidth, behavior: "smooth" });
  });

  document.getElementById("hotel-prev").addEventListener("click", () => {
    hotelSlider.scrollBy({ left: -hotelSlideWidth, behavior: "smooth" });
  });
});
