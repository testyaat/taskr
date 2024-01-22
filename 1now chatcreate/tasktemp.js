const modal = document.querySelector("#myModal");
const btn4 = document.querySelectorAll(".btn4");
const closeModal = document.getElementsByClassName("modalClose")[0];

for (let i = 0; i < btn4.length; i++) {
  btn4[i].addEventListener("click", function () {
    modal.style.display = "block";
  });
}
btn4.onclick = function () {
  modal.style.display = "block";
};
closeModal.onclick = function () {
  modal.style.display = "none";
};
window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
};