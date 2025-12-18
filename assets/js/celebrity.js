        const slideshow = document.getElementById("slideshow");

        // Define celebrity collections (each with 3 image paths)
        const collections = [
            { name: "vinicius junior", images: ["vini1.jpg","vini22.jpg","soulier.jpg"] },
            { name: "Mike Tyson",         images: ["tyson1.jpg","tyson22.jpg","gants.jpg"]     }
        ];

        let celebIndex = 0;
let isTransitioning = false;

// Create the initial layout
function createColumns(collection) {
  slideshow.innerHTML = "";
  collection.images.forEach((src, i) => {
    const col = document.createElement("div");
    col.className = "image-column";
    const img = document.createElement("img");
    img.src = `/projet-ecommerce/assets/images/${src}`;
    img.className = "active";
    col.appendChild(img);
    slideshow.appendChild(col);
  });

  // Title
  const title = document.createElement("h2");
  title.className = "collection-title";
  title.textContent = collection.name;
  slideshow.appendChild(title);
}

// Slide to next celebrity (upward one by one)
function nextCollection() {
  if (isTransitioning) return;
  isTransitioning = true;

  const current = collections[celebIndex];
  celebIndex = (celebIndex + 1) % collections.length;
  const next = collections[celebIndex];

  const cols = document.querySelectorAll(".image-column");

  cols.forEach((col, i) => {
    const oldImg = col.querySelector("img.active");
    const newImg = document.createElement("img");
    newImg.src = `/projet-ecommerce/assets/images/${next.images[i]}`;
    col.appendChild(newImg);

    //  Force reflow before triggering transition
    void newImg.offsetWidth; 

    // Animate upward after small delay (staggered)
    setTimeout(() => {
      oldImg.classList.add("prev");
      newImg.classList.add("active");
    }, i * 300);

    // Remove old image after animation
    setTimeout(() => {
      if (oldImg && oldImg.parentNode) oldImg.remove();
    }, i * 300 + 1000);
  });

  // Update celebrity name when animation finishes
  const title = document.querySelector(".collection-title");
  setTimeout(() => {
    title.textContent = next.name;
    isTransitioning = false;
  }, cols.length * 300 + 800);
}


// Init slideshow
createColumns(collections[celebIndex]);
setInterval(nextCollection, 7000);