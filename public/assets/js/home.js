const text = "រៀនសរសេរកូដដោយឥតគិតថ្លៃ ជាភាសាខ្មែរ ចាប់ពីមូលដ្ឋាន ងាយយល់ ។";
const speed = 80;      // typing speed
const eraseSpeed = 40;
const delay = 1500;

let index = 0;
let isDeleting = false;
const el = document.getElementById("typingText");

function typeEffect(){
  if(!isDeleting){
    el.textContent = text.slice(0, index++);
    if(index > text.length){
      setTimeout(() => isDeleting = true, delay);
    }
  }else{
    el.textContent = text.slice(0, index--);
    if(index < 0){
      isDeleting = false;
      index = 0;
    }
  }
  setTimeout(typeEffect, isDeleting ? eraseSpeed : speed);
}

typeEffect();

