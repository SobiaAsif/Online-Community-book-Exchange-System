</main>

<!-- Lightbox -->
<div class="lightbox" id="lightbox">
    <button class="lightbox-close" onclick="closeLightbox()">×</button>
    <div class="lightbox-nav">
        <button onclick="changeImage(-1)">❮</button>
        <button onclick="changeImage(1)">❯</button>
    </div>
    <img class="lightbox-content" id="lightbox-img">
</div>

<footer style="text-align:center; padding:2rem; color:var(--text-muted); background:var(--card); border-top:1px solid var(--border);">
    Prototype • PHP + MySQL
</footer>

<!-- Lightbox JavaScript -->
<script>
let currentImageIndex = 0;
let images = [];

function openLightbox(imageArray, index) {
    images = imageArray;
    currentImageIndex = index;
    document.getElementById('lightbox-img').src = images[currentImageIndex];
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = 'auto';
    images = [];
}

function changeImage(direction) {
    currentImageIndex += direction;
    
    if (currentImageIndex >= images.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = images.length - 1;
    }
    
    document.getElementById('lightbox-img').src = images[currentImageIndex];
}

// Close lightbox when clicking on the background
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Close with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>

</body>
</html>