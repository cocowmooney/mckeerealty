// McKee Realty - Main JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Mobile menu toggle
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Fade-in observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.fade-in').forEach(el => observer.observe(el));
    
    // Gallery thumbnail switching
    const mainImage = document.getElementById('main-image');
    const thumbs = document.querySelectorAll('.gallery-thumb');
    thumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            if (mainImage) {
                mainImage.src = this.dataset.full;
                mainImage.alt = this.dataset.alt || '';
            }
            thumbs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Price formatting
    document.querySelectorAll('.format-price').forEach(el => {
        const price = parseFloat(el.dataset.price);
        if (!isNaN(price)) {
            el.textContent = '$' + price.toLocaleString('en-US');
        }
    });
    
    // Contact form validation
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const name = document.getElementById('cf_name');
            const email = document.getElementById('cf_email');
            const msg = document.getElementById('cf_message');
            let valid = true;
            
            [name, email, msg].forEach(f => {
                if (f) {
                    f.classList.remove('border-red-500');
                    const err = f.parentElement.querySelector('.error-msg');
                    if (err) err.remove();
                }
            });
            
            if (name && !name.value.trim()) {
                showError(name, 'Name is required');
                valid = false;
            }
            if (email && !email.value.trim()) {
                showError(email, 'Email is required');
                valid = false;
            } else if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                showError(email, 'Valid email is required');
                valid = false;
            }
            if (msg && !msg.value.trim()) {
                showError(msg, 'Message is required');
                valid = false;
            }
            
            if (!valid) e.preventDefault();
        });
    }
    
    function showError(field, msg) {
        field.classList.add('border-red-500');
        const err = document.createElement('p');
        err.className = 'error-msg text-red-500 text-sm mt-1';
        err.textContent = msg;
        field.parentElement.appendChild(err);
    }
});