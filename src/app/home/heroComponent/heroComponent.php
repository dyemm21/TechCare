<div class="hero-back">
    <img src="/public/background11.jpg" alt="background-image" class="hero-background-image">
    <div class="hero-container">

        <div class="hero-slider">
            <div class="hero-slides">
                <div class="hero-single-slide">
                    <div class="hero-section">
                        <div class="hero-content">
                            <h1><span class="highlight">Available</span> to fix your <br> Smartphone & Laptops</h1>
                            <p>We provide fast and reliable repair services for your smartphones and laptops, ensuring they work like new. Trust our experts to handle all your tech issues with care and precision. Your satisfaction is our top priority, and we guarantee exceptional results. Experience hassle-free repairs with our dedicated support team.</p>
                            <div class="hero-buttons">
                                <button class="btn-primary">
                                    <a href="?page=services" >View Services</a>
                                </button>
                                <button class="btn-secondary">
                                    <a href="?page=contact" >Contact Us</a>
                                </button>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="/public/service-background.png" alt="Mobile Repair">
                        </div>
                    </div>
                </div>

                <div class="hero-single-slide">
                    <div class="hero-section">
                        <div class="hero-content">
                            <h1><span class="highlight">Professional</span> repair services for <br> iPhone & Macbook</h1>
                            <p>
                                Our professional repair services specialize in iPhones and MacBooks, delivering expert solutions you can rely on. Let us bring your Apple devices back to peak performance. Whether it’s a screen replacement or software issue, we handle it all with precision. Count on us for fast turnaround times and unmatched quality.</p>
                            <div class="hero-buttons">
                                <button class="btn-primary">
                                    <a href="?page=services" >View Services</a>
                                </button>
                                <button class="btn-secondary">
                                    <a href="?page=contact" >Contact Us</a>
                                </button>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="/public/service-background.png" alt="Mobile Repair">
                        </div>
                    </div>
                </div>

                <div class="hero-single-slide">
                    <div class="hero-section">
                        <div class="hero-content">
                            <h1><span class="highlight">Expert</span> solutions for your <br> Tablet & iPad</h1>
                            <p>We offer expert repair solutions for Tablets and iPads, restoring their functionality with precision. Count on us to keep your devices running smoothly. From fixing cracked screens to resolving software glitches, we provide comprehensive services tailored to your needs. Our team uses high-quality parts to ensure long-lasting repairs. Trust us to get your devices back to peak performance in no time.</p>
                            <div class="hero-buttons">
                                <button class="btn-primary">
                                    <a href="?page=services" >View Services</a>
                                </button>
                                <button class="btn-secondary">
                                    <a href="?page=contact" >Contact Us</a>
                                </button>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="/public/service-background.png" alt="Mobile Repair">
                        </div>
                    </div>
                </div>

            </div>
            <button class="slider-prev" onclick="prevSlide()">&#10094</button>
            <button class="slider-next" onclick="nextSlide()">&#10095</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const slides = document.querySelectorAll(".hero-single-slide");
        const prevButton = document.querySelector(".slider-prev");
        const nextButton = document.querySelector(".slider-next");
        let slideIndex = 0;
        let intervalId;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle("displaySlide", i === index);
            });
        }

        function nextSlide() {
            slideIndex = (slideIndex + 1) % slides.length;
            showSlide(slideIndex);
        }

        function prevSlide() {
            slideIndex = (slideIndex - 1 + slides.length) % slides.length;
            showSlide(slideIndex);
        }

        function startAutoSlide() {
            intervalId = setInterval(nextSlide, 5000);
        }

        function stopAutoSlide() {
            clearInterval(intervalId);
        }

        prevButton.addEventListener("click", () => {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });

        nextButton.addEventListener("click", () => {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });

        showSlide(slideIndex);
        startAutoSlide();
    });
</script>
